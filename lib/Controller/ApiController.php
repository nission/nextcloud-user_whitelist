<?php
namespace OCA\UserWhitelist\Controller;

use Exception;
use OCA\UserWhitelist\Service\WhitelistService;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\ILogger;
use OCP\IRequest;

class ApiController extends OCSController
{
    const SUCCESS = 200;

    /** @var IRequest */
    protected $request;
    /** @var WhitelistService */
    private $whitelistService;

    protected $logger;

    public function __construct($AppName, IRequest $request, ILogger $logger, WhitelistService $whitelistService)
    {
        parent::__construct($AppName, $request);
        $this->logger = $logger;
        $this->whitelistService = $whitelistService;
    }

    /**
     * @PublicPage
     * @NoCSRFRequired
     */
    public function add()
    {
        throw new Exception('test', 1);
        $this->whitelistService->addUser($this->request->getParam('username'), 'add from admin');

        return new DataResponse(['msg' => 'success', 'code' => self::SUCCESS]);
    }

    /**
     * @PublicPage
     * @NoCSRFRequired
     */
    public function batchAdd()
    {
        $usernames = explode(',', $this->request->getParam('usernames'));
        $this->whitelistService->batchAddUser($usernames, 'batch by ' . $this->request->getParam('admin'));
        return new DataResponse(['msg' => 'success', 'code' => self::SUCCESS]);
    }

    /**
     * @PublicPage
     * @NoCSRFRequired
     */
    public function sync()
    {
        $this->whitelistService->syncUser(
            $this->request->getParam('username', ''),
            (int)$this->request->getParam('status', 0),
            'sync by ' . $this->request->getParam('admin', '')
        );

        return new DataResponse(['msg' => 'success', 'code' => self::SUCCESS]);
    }

    /**
     * @PublicPage
     * @NoCSRFRequired
     */
    public function enable()
    {
        $this->whitelistService->enableUser($this->request->getParam('username'), 'enable from admin');

        return new DataResponse(['msg' => 'success', 'code' => self::SUCCESS]);
    }

    /**
     * @PublicPage
     * @NoCSRFRequired
     */
    public function disable()
    {
        $this->whitelistService->disableUser($this->request->getParam('username'), 'disable from admin');

        return new DataResponse(['msg' => 'success', 'code' => self::SUCCESS]);
    }
}
