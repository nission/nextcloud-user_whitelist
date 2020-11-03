<?php
namespace OCA\UserWhitelist\Controller;

use OCA\UserWhitelist\Service\WhitelistService;
use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

class UserWhitelistController extends Controller {
	private $userId;
	private $whitelistService;

	public function __construct($AppName, IRequest $request, $UserId, WhitelistService $whitelistService){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->whitelistService = $whitelistService;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @SubAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		return new TemplateResponse('userwhitelist', 'index');  // templates/index.php
	}

	/**
	 * @SubAdminRequired
	 */
	public function show() {

	}

	public function add() {

	}

	public function enable() {

	}

	public function disable() {

	}

}
