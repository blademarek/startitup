<?php

namespace Crm\BenefitModule\Api;

use Crm\ApiModule\Api\ApiHandler;
use Crm\ApiModule\Api\JsonValidationTrait;
use Crm\BenefitModule\Repositories\BenefitRepository;
use Crm\BenefitModule\Repositories\UserBenefitRepository;
use Crm\UsersModule\Auth\UsersApiAuthorizationInterface;
use Exception;
use League\Fractal\ScopeFactoryInterface;
use Nette\Http\IResponse;
use Tomaj\NetteApi\Response\JsonApiResponse;
use Tomaj\NetteApi\Response\ResponseInterface;

class BenefitsHandler extends ApiHandler
{
    use JsonValidationTrait;

    public function __construct(
        private UserBenefitRepository $userBenefitRepository,
        private BenefitRepository $benefitRepository,
        ScopeFactoryInterface $scopeFactory = null
    ) {
        parent::__construct($scopeFactory);
    }

    public function params(): array
    {
        return [];
    }

    /**
     * @throws Exception
     */
    public function handle(array $params): ResponseInterface
    {
        // auth checks
        $authorization = $this->getAuthorization();
        if (!($authorization instanceof UsersApiAuthorizationInterface)) {
            throw new Exception("Wrong authorization service used. Should be 'UsersApiAuthorizationInterface'");
        }

        $data = $authorization->getAuthorizedData();
        if (empty($data['token']->user)) {
            return $this->formatResponse(IResponse::S403_Forbidden, 'error', [
                'message' => 'Cannot authorize user',
                'code' => 'cannot_authorize_user'
            ]);
        }

        // api input validation
        $result = $this->validateInput(__DIR__ . '/benefits.schema.json', $this->rawPayload());
        if ($result->hasErrorResponse()) {
            return $result->getErrorResponse();
        }

        $params = $result->getParsedObject();

        // loading user benefits
        $userBenefits = $this->userBenefitRepository->getUserBenefitIds($params->user_id);
        $userBenefitsData = $this->benefitRepository->getBenefitsById($userBenefits);

        return $this->formatResponse(IResponse::S200_OK, 'ok', $this->formatResponseData($userBenefitsData, $params->user_id));
    }

    private function formatResponse(int $code, string $status, array $payload): ResponseInterface
    {
        return new JsonApiResponse($code, array_merge(['status' => $status], $payload));
    }

    private function formatResponseData(array $benefits, int $userId): array
    {
        return [
            'data' => [
                'user_id' => $userId,
                'benefits' => $this->benefitsToArray($benefits)
            ]
        ];
    }

    private function benefitsToArray($benefits): array
    {
        // ActiveRow to array conversion
        $tmpBenefits = [];

        foreach ($benefits as $benefit) {
            $tmpBenefits[$benefit->id] = $benefit->toArray();
        }

        return $tmpBenefits;
    }
}
