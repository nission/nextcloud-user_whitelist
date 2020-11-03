<?php
namespace OCA\UserWhiteList\Settings;

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

class AdminSection implements IIconSection
{
    /** @var IL10N */
    private $l;
	/** @var IURLGenerator */
	private $url;

	public function __construct(IL10N $l, IURLGenerator $url) {
		$this->l = $l;
		$this->url = $url;
	}

    public function getIcon()
    {
		return $this->url->imagePath('accessibility', 'app-dark.svg');
    }

    public function getID()
    {
        return 'user_whitelist';
    }

    public function getName()
    {
        return $this->l->t('User Whitelist');
    }

    public function getPriority()
    {
        return 90;
    }
}