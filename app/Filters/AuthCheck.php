<?php 
    namespace App\Filters;

    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use CodeIgniter\Filters\FilterInterface;
    
    class AuthCheck implements FilterInterface
    {
        public function before(RequestInterface $request, $arguments = null)
        {
            if (is_null(session()->get('user_id'))) {
                return redirect()->to('/login');
            }
        }
    
        public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
        {
            // No need to implement anything here for now
        }
    }
    
?>