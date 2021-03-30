<?php
/**
 * The MIT License (MIT).
 *
 * Copyright (c) 2017-2021 Michael Dekker (https://github.com/firstred)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author    Michael Dekker <git@michaeldekker.nl>
 * @copyright 2017-2021 Michael Dekker
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace Firstred\PostNL\Service;

use Psr\Cache\InvalidArgumentException as PsrCacheInvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use ReflectionException;
use Firstred\PostNL\Entity\Request\CompleteStatus;
use Firstred\PostNL\Entity\Request\CompleteStatusByPhase;
use Firstred\PostNL\Entity\Request\CompleteStatusByReference;
use Firstred\PostNL\Entity\Request\CompleteStatusByStatus;
use Firstred\PostNL\Entity\Request\CurrentStatus;
use Firstred\PostNL\Entity\Request\CurrentStatusByPhase;
use Firstred\PostNL\Entity\Request\CurrentStatusByReference;
use Firstred\PostNL\Entity\Request\CurrentStatusByStatus;
use Firstred\PostNL\Entity\Request\GetSignature;
use Firstred\PostNL\Entity\Response\CompleteStatusResponse;
use Firstred\PostNL\Entity\Response\CurrentStatusResponse;
use Firstred\PostNL\Entity\Response\GetSignatureResponseSignature;
use Firstred\PostNL\Exception\ApiException;
use Firstred\PostNL\Exception\CifDownException;
use Firstred\PostNL\Exception\CifException;
use Firstred\PostNL\Exception\HttpClientException;
use Firstred\PostNL\Exception\ResponseException;

/**
 * Class ShippingStatusService.
 *
 * @method CurrentStatusResponse         currentStatus(CurrentStatus|CurrentStatusByReference|CurrentStatusByPhase|CurrentStatusByStatus $currentStatus)
 * @method RequestInterface              buildCurrentStatusRequest(CurrentStatus|CurrentStatusByReference|CurrentStatusByPhase|CurrentStatusByStatus $currentStatus)
 * @method CurrentStatusResponse         processCurrentStatusResponse(mixed $response)
 * @method CompleteStatusResponse        completeStatus(CompleteStatus|CompleteStatusByReference|CompleteStatusByPhase|CompleteStatusByStatus $completeStatus)
 * @method RequestInterface              buildCompleteStatusRequest(CompleteStatus|CompleteStatusByReference|CompleteStatusByPhase|CompleteStatusByStatus $completeStatus)
 * @method CompleteStatusResponse        processCompleteStatusResponse(mixed $response)
 * @method GetSignatureResponseSignature getSignature(GetSignature $getSignature)
 * @method RequestInterface              buildGetSignatureRequest(GetSignature $getSignature)
 * @method GetSignature                  processGetSignatureResponse(mixed $response)
 *
 * @since 1.2.0
 */
interface ShippingStatusServiceInterface extends ServiceInterface
{
    /**
     * Gets the current status.
     *
     * This is a combi-function, supporting the following:
     * - CurrentStatus (by barcode):
     *   - Fill the Shipment->Barcode property. Leave the rest empty.
     * - CurrentStatusByReference:
     *   - Fill the Shipment->Reference property. Leave the rest empty.
     * - CurrentStatusByPhase:
     *   - Fill the Shipment->PhaseCode property, do not pass Barcode or Reference.
     *     Optionally add DateFrom and/or DateTo.
     * - CurrentStatusByStatus:
     *   - Fill the Shipment->StatuCode property. Leave the rest empty.
     *
     * @param CurrentStatus|CurrentStatusByReference|CurrentStatusByPhase|CurrentStatusByStatus $currentStatus
     *
     * @return CurrentStatusResponse
     *
     * @throws ApiException
     * @throws CifDownException
     * @throws CifException
     * @throws ResponseException
     * @throws PsrCacheInvalidArgumentException
     * @throws ReflectionException
     * @throws HttpClientException
     *
     * @since 1.0.0
     */
    public function currentStatusREST($currentStatus);

    /**
     * Gets the complete status.
     *
     * This is a combi-function, supporting the following:
     * - CurrentStatus (by barcode):
     *   - Fill the Shipment->Barcode property. Leave the rest empty.
     * - CurrentStatusByReference:
     *   - Fill the Shipment->Reference property. Leave the rest empty.
     * - CurrentStatusByPhase:
     *   - Fill the Shipment->PhaseCode property, do not pass Barcode or Reference.
     *     Optionally add DateFrom and/or DateTo.
     * - CurrentStatusByStatus:
     *   - Fill the Shipment->StatuCode property. Leave the rest empty.
     *
     * @param CompleteStatus $completeStatus
     *
     * @return CompleteStatusResponse
     *
     * @throws ApiException
     * @throws CifDownException
     * @throws CifException
     * @throws ResponseException
     * @throws PsrCacheInvalidArgumentException
     * @throws ReflectionException
     * @throws HttpClientException
     *
     * @since 1.0.0
     */
    public function completeStatusREST(CompleteStatus $completeStatus);

    /**
     * Gets the complete status.
     *
     * This is a combi-function, supporting the following:
     * - CurrentStatus (by barcode):
     *   - Fill the Shipment->Barcode property. Leave the rest empty.
     * - CurrentStatusByReference:
     *   - Fill the Shipment->Reference property. Leave the rest empty.
     * - CurrentStatusByPhase:
     *   - Fill the Shipment->PhaseCode property, do not pass Barcode or Reference.
     *     Optionally add DateFrom and/or DateTo.
     * - CurrentStatusByStatus:
     *   - Fill the Shipment->StatusCode property. Leave the rest empty.
     *
     * @param GetSignature $getSignature
     *
     * @return GetSignatureResponseSignature
     *
     * @throws ApiException
     * @throws CifDownException
     * @throws CifException
     * @throws ResponseException
     * @throws PsrCacheInvalidArgumentException
     * @throws ReflectionException
     * @throws HttpClientException
     *
     * @since 1.0.0
     */
    public function getSignatureREST(GetSignature $getSignature);

    /**
     * Build the CurrentStatus request for the REST API.
     *
     * This function auto-detects and adjusts the following requests:
     * - CurrentStatus
     * - CurrentStatusByReference
     * - CurrentStatusByPhase
     * - CurrentStatusByStatus
     *
     * @param CurrentStatus|CurrentStatusByReference|CurrentStatusByPhase|CurrentStatusByStatus $currentStatus
     *
     * @return RequestInterface
     *
     * @throws ReflectionException
     *
     * @since 1.0.0
     */
    public function buildCurrentStatusRequestREST($currentStatus);

    /**
     * Process CurrentStatus Response REST.
     *
     * @param mixed $response
     *
     * @return CurrentStatusResponse
     *
     * @throws ResponseException
     * @throws ReflectionException
     * @throws HttpClientException
     *
     * @since 1.0.0
     */
    public function processCurrentStatusResponseREST($response);

    /**
     * Build the CompleteStatus request for the REST API.
     *
     * This function auto-detects and adjusts the following requests:
     * - CompleteStatus
     * - CompleteStatusByReference
     * - CompleteStatusByPhase
     * - CompleteStatusByStatus
     *
     * @param CompleteStatus $completeStatus
     *
     * @return RequestInterface
     *
     * @throws ReflectionException
     *
     * @since 1.0.0
     */
    public function buildCompleteStatusRequestREST(CompleteStatus $completeStatus);

    /**
     * Process CompleteStatus Response REST.
     *
     * @param mixed $response
     *
     * @return CompleteStatusResponse|null
     *
     * @throws ResponseException
     * @throws ReflectionException
     * @throws HttpClientException
     *
     * @since 1.0.0
     */
    public function processCompleteStatusResponseREST($response);

    /**
     * Build the GetSignature request for the REST API.
     *
     * @param GetSignature $getSignature
     *
     * @return RequestInterface
     * @throws ReflectionException
     */
    public function buildGetSignatureRequestREST(GetSignature $getSignature);

    /**
     * Process GetSignature Response REST.
     *
     * @param mixed $response
     *
     * @return GetSignatureResponseSignature|null
     *
     * @throws ResponseException
     * @throws ReflectionException
     * @throws HttpClientException
     *
     * @since 1.0.0
     */
    public function processGetSignatureResponseREST($response);
}
