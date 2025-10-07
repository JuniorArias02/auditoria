<?php
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Mi API",
 *     version="1.0.0",
 *     description="Documentación generada con swagger-php"
 * )
 */

/**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Listar usuarios",
 *     @OA\Response(response="200", description="OK")
 * )
 */

/**
 * @OA\Post(
 *     path="/api/users",
 *     summary="Crear usuario",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Usuario creado")
 * )
 */
