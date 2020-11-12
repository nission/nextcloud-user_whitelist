<?php

namespace OCA\UserWhitelist\Settings;

use OCA\UserWhitelist\Service\WhitelistService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

class Admin implements ISettings {

    /**
     * @var WhitelistService $service
     */
    private $service;
    
    public function __construct(WhitelistService $whitelist)
    {
        $this->service = $whitelist;
    }
	/**
	 * @return TemplateResponse
	 */
	public function getForm() {
        $parameters = [
            'users' => $this->service->paginationUser(0, 100)
        ];

        return new TemplateResponse('userwhitelist', 'settings/index', $parameters, '');
    }

	/**
	 * @return string the section ID, e.g. 'sharing'
	 */
	public function getSection() {
		return 'user_whitelist';
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is required to return a value between 0 and 100.
	 *
	 * E.g.: 70
	 */
	public function getPriority() {
		return 90;
	}
}