<?php

declare(strict_types=1);

namespace App\Controller;

use App\GraphQL\Type\MutationType;
use App\GraphQL\Type\QueryType;
use App\GraphQL\TypeRegistry;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;

class GraphQL
{
    static public function handle()
    {
        try {
            // Schema creation
            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery(new QueryType())
                    ->setMutation(new MutationType())
                // ->setTypeLoader(fn(string $typeName) => new TypeRegistry($typeName))
            );

            // Read and decode input
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }

            $input = json_decode($rawInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Invalid JSON input: ' . json_last_error_msg());
            }

            $query = $input['query'] ?? null;
            if ($query === null) {
                throw new RuntimeException('No query provided in the request.');
            }

            $variableValues = $input['variables'] ?? null;

            // Execute the query
            $result = GraphQLBase::executeQuery($schema, $query, null, null, $variableValues);
            $output = $result->toArray();

            // Set HTTP response code for success
            http_response_code(200);
        } catch (Throwable $e) {
            $logFile = '/opt/lampp/htdocs/file.log';
            $logData = sprintf("[%s] Error: %s\n", date('Y-m-d H:i:s'), json_encode($e->getMessage(), JSON_PRETTY_PRINT));
            file_put_contents($logFile, $logData, FILE_APPEND);

            $output = [
                'errors' => [
                    [
                        'message' => $e->getMessage(),
                    ],
                ],
            ];
        }

        // Send the response
        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}
