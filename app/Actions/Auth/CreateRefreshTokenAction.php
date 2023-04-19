<?php

namespace App\Actions\Auth;

use App\Models\RefreshToken;
use App\Models\User;
use App\Repositories\RefreshToken\RefreshTokenRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Lorisleiva\Actions\Concerns\AsAction;
use WhichBrowser\Parser;

class CreateRefreshTokenAction
{
    use AsAction;

    public function __construct(
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        private readonly Repository $configRepository,
        private readonly Parser $parser
    ) {
    }

    public static function create(User $user, ?string $device = null): RefreshToken
    {
        return self::run($user, $device);
    }

    private function handle(User $user, ?string $device): RefreshToken
    {
        $ttl = (int) $this->configRepository->get('jwt.refresh_ttl');

        $validUntil = Carbon::now()->addMinutes($ttl);

        $data = [
            'user_id' => $user->id,
            'refresh_token' => CreateUuidTokenAction::create(RefreshToken::class, 'refresh_token'),
            'device' => $device ?? CreateDeviceIdentifierAction::create(),
            'valid_until' => $validUntil,
        ];

        return $this->refreshTokenRepository->create($data);
    }
}
