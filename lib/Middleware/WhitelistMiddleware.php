<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Arthur Schiwon <blizzz@arthur-schiwon.de>
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @author Bjoern Schiessle <bjoern@schiessle.org>
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @author Daniel Kesselberg <mail@danielkesselberg.de>
 * @author Joas Schilling <coding@schilljs.com>
 * @author Julien Veyssier <eneiluj@posteo.net>
 * @author Lukas Reschke <lukas@statuscode.ch>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 * @author Stefan Weil <sw@weilnetz.de>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 * @author Thomas Tanghus <thomas@tanghus.net>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\UserWhitelist\Middleware;

use OCA\UserWhitelist\Exception\WhitelistException;
use OCA\UserWhitelist\Service\ApiService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Response;
use OCP\AppFramework\Middleware;
use OCP\AppFramework\OCS\OCSException;
use OCP\AppFramework\OCS\OCSForbiddenException;
use OCP\AppFramework\OCSController;
use OCP\IL10N;
use OCP\ILogger;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUserSession;
use OCP\IGroupManager;

/**
 * Used to do all the authentication and checking stuff for a controller method
 * It reads out the annotations of a controller method and checks which if
 * security things should be checked and also handles errors in case a security
 * check fails
 */
class WhitelistMiddleware extends Middleware
{
    /** @var IRequest */
    private $request;
    /** @var IURLGenerator */
    private $urlGenerator;
    /** @var ILogger */
    private $logger;
    /** @var IUserSession */
    private $session;
    /** @var bool */
    private $isAdminUser;
    /** @var bool */
    private $isSubAdmin;
    /** @var IL10N */
    private $l10n;
    /** @var IUser */
    private $user;
    /** @var ApiService */
    private $api;

    public function __construct(
        IRequest $request,
        IURLGenerator $urlGenerator,
        ILogger $logger,
        IUserSession $session,
        IGroupManager $groupManager,
        IL10N $l10n,
        ApiService $api
    ) {
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
        $this->logger = $logger;
        $this->session = $session;
        $this->l10n = $l10n;
        $this->api = $api;

        if (($user = $session->getUser())) {
            $this->user = $user;
            $this->isAdminUser = $groupManager->isAdmin($user->getUID());
        }
    }

    /**
     * This runs all the security checks before a method call. The
     * security checks are determined by inspecting the controller method
     * annotations
     * @param Controller $controller the controller
     * @param string $methodName the name of the method
     * @throws \Exception when a security check fails
     *
     * @suppress PhanUndeclaredClassConstant
     */
    public function beforeController($controller, $methodName)
    {
        if ($controller instanceof OCSController) {
            $params = $this->request->getParams();
            $this->logger->warning(
                'Request api params: {params}',
                ['params' => http_build_query($params)]
            );

            if (!$this->api->isLegalRq($params)) {
                throw new OCSForbiddenException();
            }
        }
    }

    /**
     * If an SecurityException is being caught, ajax requests return a JSON error
     * response and non ajax requests redirect to the index
     * @param Controller $controller the controller that is being called
     * @param string $methodName the name of the method that will be called on
     *                           the controller
     * @param \Exception $exception the thrown exception
     * @throws \Exception the passed in exception if it can't handle it
     * @return Response a Response object or null in case that the exception could not be handled
     */
    public function afterException($controller, $methodName, \Exception $exception): Response
    {
		$this->logger->error($exception->getMessage());
        if ($controller instanceof OCSController) {
			$message = "Unkown Error";
			$code = 999999;
			if ($exception instanceof WhitelistException) {
				$message = $exception->getMessage();
				$code = $exception->getCode();
			}

            throw new OCSException($message, (int)sprintf('5%05d', $code), $exception);
        }

        throw $exception;
    }
}
