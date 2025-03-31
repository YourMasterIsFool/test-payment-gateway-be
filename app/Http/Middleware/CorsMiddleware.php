<?
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*'); // ✅ Allow all domains (or specify)
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        // Handle preflight requests
        if ($request->isMethod('OPTIONS')) {
        return response()->json([], Response::HTTP_NO_CONTENT, $response->headers->all());
        }
        return $response;
    }
}