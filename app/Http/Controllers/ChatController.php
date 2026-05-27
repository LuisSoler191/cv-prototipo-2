<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ChatController extends Controller
{
    private string $systemPrompt = <<<'PROMPT'
Eres el asistente personal de Luis Soler Valdivia, integrado en su portfolio web (luissoler.dev). Tu función es responder preguntas sobre Luis de forma cercana, honesta y profesional. Hablas en el idioma en el que te escriba el usuario (español o inglés). Eres conciso — no escribas parrafadas innecesarias. Si no sabes algo sobre Luis que no esté en tu contexto, dilo claramente en vez de inventarlo.

---

## SOBRE LUIS

**Nombre completo:** Luis Soler Valdivia
**Edad:** 21 años
**Ubicación:** Albacete, España
**Teléfono:** +34 623 208 716
**Email:** soyls191@gmail.com
**Web:** luissoler.dev
**GitHub:** github.com/LuisSoler191
**LinkedIn:** linkedin.com/in/luis-soler-valdivia

Desarrollador web junior con formación en DAW y experiencia práctica en entornos de producción real. Se especializa en back-end y desarrollo full stack con Laravel y Vue.js. Le apasiona la automatización y la integración de IA local en flujos de trabajo propios. Tiene disponibilidad total, carné de conducir B y vehículo propio.

---

## EXPERIENCIA

**Desarrollador Web · Prácticas DAW**
im3dia comunicación · Albacete
Marzo 2026 – Junio 2026

- Desarrollo full-stack (Laravel + Vue 2/3) en SPAs con soporte multiidioma
- Implementación de módulos completos full stack sobre paneles de administración
- Documentación de APIs con Swagger / OpenAPI
- Corrección de bugs y mejoras de eficiencia y seguridad
- Testing avanzado con API testing, Vitest y Playwright; pruebas automatizadas con stack de IA local
- Proyectos en producción en los que trabajó: forestales.net, sierradelsegura.com, lamanchuelarural.com

---

## PROYECTOS

**GastroFlow · KDS para restauración**
TFG · 2025–2026
Sistema de gestión de cocina flexible desde la llegada del comensal hasta el cobro conjunto o individual con QR. Moldeable para adaptarse a cualquier negocio.
Stack: Laravel, React, API REST, WebSockets, MySQL, Docker

**Portfolio Web con Chatbot asesor integrado**
2026 · En desarrollo
CV web desplegado en servidor local propio (luissoler.dev), con chatbot basado en IA local. Proyecto en continuo desarrollo.
Stack: Laravel, Vue 3, Docker, Tailscale, Caddy, SSH, llama.cpp, Qwen, OpenAPI

---

## STACK TÉCNICO

**Back-End:** PHP, Laravel, API REST, JWT, SQL, Swagger / OpenAPI
**Front-End:** JavaScript, Vue 2/3, React, Vite, HTML, CSS, Tailwind, Bootstrap
**Infra / Herramientas:** Git, GitHub, Docker, Ubuntu, Bash, SSH, npm, Playwright, Vitest, Caddy
**IA Local:** Ollama, llama.cpp, vLLM, OpenWebUI, Aider, Roo Code, Continue.dev, Qwen

---

## FORMACIÓN

- **CFGS Desarrollo de Aplicaciones Web** · MEDAC Albacete · Sep 2024 – Mar 2026
- **Bachillerato Tecnológico** · IES Diego de Siloé · Sep 2018 – Jun 2024
- **Técnico en Ciberseguridad en la Empresa 4.0** · Wolkit Solutions / areaproject · Certificado por CISCO · Oct 2024 – Ene 2025

---

## IDIOMAS

- Castellano: nativo
- Inglés: C1

---

## APTITUDES

Trabajo en equipo, resolución de problemas, aprendizaje autónomo, testing avanzado, documentación técnica, metodología ágil.

---

## INSTRUCCIONES DE COMPORTAMIENTO

- Responde siempre en el idioma del usuario (español o inglés).
- Sé directo y natural, no excesivamente formal. Luis es un tío joven y cercano.
- Si te preguntan si pueden contactar con Luis, facilita su email (soyls191@gmail.com) o redirige al formulario de contacto de la web.
- Si te preguntan por el salario o expectativas económicas de Luis, di que eso es mejor hablarlo directamente con él.
- No inventes proyectos, tecnologías ni experiencias que no estén en este contexto.
- Si alguien pregunta algo que no tiene que ver con Luis (matemáticas, recetas, política...), declina amablemente y recuerda que estás aquí para hablar sobre Luis y su perfil profesional.
PROMPT;

    public function send(Request $request)
    {
        // Rate limiting: 20 peticiones por minuto por IP
        $key = 'chat:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 20)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'error' => 'Demasiadas peticiones. Espera ' . $seconds . ' segundos.'
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $validated = $request->validate([
            'message'   => 'required|string|max:1000',
            'history'   => 'array|max:20',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        // Construir array de mensajes: system + historial previo + mensaje actual
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt],
        ];

        foreach ($validated['history'] ?? [] as $turn) {
            $messages[] = [
                'role'    => $turn['role'],
                'content' => $turn['content'],
            ];
        }

        $messages[] = [
            'role'    => 'user',
            'content' => $validated['message'],
        ];

        try {
            $headers = [
                'Content-Type'           => 'text/event-stream',
                'Cache-Control'          => 'no-cache',
                'X-Accel-Buffering'      => 'no',
                'X-Content-Type-Options' => 'nosniff',
            ];

           return response()->stream(function () use ($messages) {
                // Output buffer management — force immediate flush
                while (ob_get_level() > 0) { ob_end_flush(); }
                ob_implicit_flush(true);

                $client = new Client(['timeout' => 60, 'connect_timeout' => 10]);
                $request = (new HttpFactory())->createRequest('POST', 'http://100.74.99.27:8001/v1/chat/completions');
                $request = $request->withHeader('Authorization', 'Bearer dummy');
                $request = $request->withHeader('Content-Type', 'application/json');

                $body = json_encode([
                    'model'       => 'qwen3.6-35b',
                    'messages'    => $messages,
                    'max_tokens'  => 8192,
                    'temperature' => 0.7,
                    'stream'      => true,
                ]);

                $response = $client->send($request, [
                    'body'   => $body,
                    'stream' => true,
                ]);

                if ($response->getStatusCode() >= 400) {
                    echo "data: {\"error\":\"El modelo no está disponible en este momento.\"}\n\n";
                    @ob_flush();
                    flush();
                    return;
                }

                $stream = $response->getBody();
                $buffer = '';

                while (!$stream->eof()) {
                    $chunk = $stream->read(256);
                    if ($chunk === false || $chunk === '') break;

                    $buffer .= $chunk;

                    // Process complete lines from the buffer
                    while (($newlinePos = strpos($buffer, "\n")) !== false) {
                        $line = rtrim(substr($buffer, 0, $newlinePos), "\r");
                        $buffer = substr($buffer, $newlinePos + 1);

                        if (str_starts_with($line, 'data: ')) {
                            $data = substr($line, 6);
                            if ($data === '[DONE]') {
                                echo "data: [DONE]\n\n";
                                @ob_flush();
                                flush();
                                return;
                            }
                            $json = json_decode($data, true);
                            if ($json) {
                                $token = $json['choices'][0]['delta']['content'] ?? '';
                                if ($token !== '') {
                                    echo "data: " . json_encode(['token' => $token]) . "\n\n";
                                    @ob_flush();
                                    flush();
                                }
                            }
                        }
                    }
                }

                // Process any remaining data in buffer (last line without newline)
                if (!empty($buffer)) {
                    $line = rtrim($buffer, "\r");
                    if (str_starts_with($line, 'data: ')) {
                        $data = substr($line, 6);
                        if ($data !== '[DONE]') {
                            $json = json_decode($data, true);
                            if ($json) {
                                $token = $json['choices'][0]['delta']['content'] ?? '';
                                if ($token !== '') {
                                    echo "data: " . json_encode(['token' => $token]) . "\n\n";
                                    @ob_flush();
                                    flush();
                                }
                            }
                        }
                    }
                }

                echo "data: [DONE]\n\n";
                @ob_flush();
                flush();
            }, 200, $headers);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudo conectar con el modelo.'
            ], 503);
        }
    }
}
