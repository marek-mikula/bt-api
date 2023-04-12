<?php

namespace App\Services\Mfa;

use App\Models\MfaToken;
use Exception;

class MfaTokenResolver
{
    private ?MfaToken $mfaToken = null;

    /**
     * @throws Exception
     */
    public function getMfaToken(): MfaToken
    {
        if ($this->mfaToken === null) {
            throw new Exception('Trying to retrieve empty token. Haven\'t you forgot the MFA token middleware?');
        }

        return $this->mfaToken;
    }

    public function setMfaToken(MfaToken $mfaToken): void
    {
        $this->mfaToken = $mfaToken;
    }
}
