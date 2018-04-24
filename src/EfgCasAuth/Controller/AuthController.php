<?php

namespace EfgCasAuth\Controller;

use phpCAS;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class AuthController extends AbstractActionController
{

    /**
     *
     * @var Request
     */
    protected $request = null;

    /**
     * @var AuthenticationServiceInterface
     */
    protected $authService = null;
    protected $configCas = null;
    protected $cas_inited = null;
    protected $session = null;

    public function __construct(AuthenticationServiceInterface $authService, SessionManager $session, $configCas)
    {
        $this->authService = $authService;
        $this->configCas = $configCas;
        $this->cas_inited = false;

        $this->session = $session;
    }

    public function loginAction()
    {
        //if already login, redirect to index page
//        if ($this->authService->hasIdentity()) {
//            return $this->redirect()->toRoute('home');
//        }
        return $this->authenticate();
    }

    private function authenticate()
    {
        $configCas = $this->configCas;

        // Enable debugging
        if ($configCas['cas_debug']) {
            phpCAS::setDebug($configCas['cas_debug_file']);
        }

        // Initialize phpCAS
        phpCAS::client(
            $configCas['cas_version'],
            $configCas['cas_hostname'],
            $configCas['cas_port'],
            $configCas['cas_path'],
            true
        );

        // For production use set the CA certificate that is the issuer of the cert
        // on the CAS server and uncomment the line below
        // set SSL validation for the CAS server
        // For quick testing you can disable SSL validation of the CAS server.
        // THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
        // VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
        if ($configCas['cas_validation']) {
            phpCAS::setCasServerCACert($configCas['cas_server_ca_cert_path']);
        } else {
            phpCAS::setNoCasServerValidation();
        }

        // Handle SingleLogout SLO
        phpCAS::handleLogoutRequests(false);

        $serviceUrl = $this->url()->fromRoute('login', array(), array('force_canonical' => true));
        $serviceUrl = str_replace( 'http://', 'https://', $serviceUrl );
        phpCAS::setFixedServiceURL($serviceUrl);

        // Check for logout request
        if (isset($_REQUEST['logout'])) {
            // Clear la session ZF2
            //youcef  $manager = new \Zend\Session\SessionManager();
            //$manager->forgetMe();
            $this->session->forgetMe();
            $this->authService->clearIdentity();

            // Logout de CAS
            $url = $this->url()->fromRoute('home', array(), array('force_canonical' => true));
            phpCAS::logout(array('url' => $url, 'service' => $url));
        }

        if (phpCAS::isAuthenticated()) {
            $adapter = $this->authService->getAdapter();

            // setCredential must have a password
            // but with CAS value is empty
//            $adapter->setIdentityValue(phpCAS::getUser());
//            $adapter->setCredentialValue('');
            $adapter->setIdentity(phpCAS::getUser());
            $adapter->setCredential('');

            $authResult = $this->authService->authenticate();

            // Test user in Database
            if ($authResult->isValid()) {
                $this->authService->getStorage()->write($authResult->getIdentity());
                // Back to index

                $redirectTo = $this->url()->fromRoute('home', array(), array('force_canonical' => true));
                if ($this->request->getQuery('redirectTo') !== null) {
                    $redirectTo = $this->request->getQuery('redirectTo');
                }
                if ($configCas['cas_redirect_route_after_login']
                    && !empty($configCas['cas_redirect_route_after_login'])) {
                    $redirectTo = $this->url()->fromRoute(
                        $configCas['cas_redirect_route_after_login'],
                        array(),
                        array('force_canonical' => true)
                    );
                }
            } else {
                $container = new Container('noAuth');
                $container->login = $authResult->getIdentity();
                $container->loginMessage = $authResult->getMessages();
//                $redirectTo = $this->redirect()->toRoute($configCas['no_account_route']);
                $redirectTo = $this->url()->fromRoute($configCas['no_account_route'], array(), array('force_canonical' => true));
            }
        } else {
            // hey, authenticate
            phpCAS::forceAuthentication();
            die();
        }

        return $this->redirect()->toUrl($redirectTo);
    }

    public function logoutAction()
    {
        // if not login, redirect to home page
        if (!$this->authService->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        //YOUCEF
        //$manager = new \Zend\Session\SessionManager();
        //$manager->forgetMe();
        $this->session->forgetMe();
        $this->authService->clearIdentity();

        $configCas = $this->configCas;

        // Enable debugging
        if ($configCas['cas_debug']) {
            phpCAS::setDebug($configCas['cas_debug_file']);
        }

        // Initialize phpCAS
        phpCAS::client(
            $configCas['cas_version'],
            $configCas['cas_hostname'],
            $configCas['cas_port'],
            $configCas['cas_path'],
            true
        );

        if ($configCas['cas_validation']) {
            phpCAS::setCasServerCACert($configCas['cas_server_ca_cert_path']);
        } else {
            phpCAS::setNoCasServerValidation();
        }

        phpCAS::handleLogoutRequests();

        $url = $this->url()->fromRoute('home', array(), array('force_canonical' => true));

        phpCAS::logout(array('url' => $url, 'service' => $url));
        return $this->redirect()->toRoute('home');
    }

    private function getCurrentUrl()
    {
        $uri = $this->getRequest()->getUri();
        $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost() . $uri->getPath());

        return $base;
    }
}
