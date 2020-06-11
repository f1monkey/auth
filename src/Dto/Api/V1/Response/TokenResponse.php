<?php
declare(strict_types=1);

namespace App\Dto\Api\V1\Response;

use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TokenResponse
 *
 * @package App\Dto\Api\V1\Response
 */
class TokenResponse
{
    /**
     * Auth token
     *
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Serializer\SerializedName("token")
     * @Serializer\Type("string")
     *
     * @SWG\Property(title="Auth token", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTE4ODQ3MzYsImV4cCI6MTU5MTg4NTYzNiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidXNlciJ9.fysVadB_6juDt2cU7m21bzLK7LXZBWyLaNS_uqkJuplDkY0GXQ0w6ZQ756LWcoyCePLu5Qz3NXQUJbgtDC4K-b7p90ozLQrBZWPUb5J5pZqk9ko9jgx1x5Jatck0As5eC74--TJon7Uy0Fl8OSTYd_LQ31ZWzzYvPSita9OuRmS2JqNlc9Pxs9CS2A-OL0PeO0eolCR0W98nd5EtaVG01oI64-Kn0pamihwCPam6TBrL38Pf40MaFYmGSIBAWatWEd8KO2JSjxiqNX_O3nH-Udc-JsQuUAhPrA2gJRs0JNdY09FdQ4IS5rZUchwHPSqGHtWNAiGeq_iiosUe7IgW84pK3jLOSy0i2QcID-iRh3dCf9Kj12A3iOYmvBetVB66_sop8LNbo6w-gt8N8DpZPUT_Rm4WLCrXpu9_P6Q3VAHS54dDiXl0zBM4WZ0EZGxdMtsanSObZd535i_7NABoqjA8U-bwsxOnKyvxmGzZwR6b8vOorcin6NlFBBPJ501XfKM7cd-fmf85HnxBil_iS4oZghnpbsWjp7DqOkgdnV-jksKlATSgVUvZFVLYiOdaroYoXAEUzW95vUd6c6C3POfRbyJMR4sIgDDn1WEqjOiyu-mvoqWU3WaPnrq4nk4MSBrMkthw8d5929voxpx87feQb0ET-ztlDCbvUwA3kPc")
     */
    protected string $token;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return TokenResponse
     */
    public function setToken(string $token): TokenResponse
    {
        $this->token = $token;

        return $this;
    }
}