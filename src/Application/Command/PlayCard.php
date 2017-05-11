<?php declare(strict_types=1);

namespace CardBattleGame\Application\Command;

use Prooph\Common\Messaging\Command;
use Ramsey\Uuid\Uuid;

class PlayCard extends Command
{
    private $type;
    private $hp;
    private $mp;
    private $gameId;

    public function __construct(Uuid $gameId, string $type, int $hp, int $mp)
    {
        $this->type = $type;
        $this->hp = $hp;
        $this->mp = $mp;
        $this->gameId = $gameId;

        $this->init();
    }

    public function getGameId(): Uuid
    {
        return $this->gameId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getHp(): int
    {
        return $this->hp;
    }

    public function getMp(): int
    {
        return $this->mp;
    }

    protected function setPayload(array $payload): void
    {
        $this->type = $payload['type'];
        $this->hp = $payload['hp'];
        $this->mp = $payload['mp'];
        $this->gameId = $payload['gameId'];
    }

    public function payload(): array
    {
        return [
            'type' => $this->getType(),
            'hp' => $this->getHp(),
            'mp' => $this->getMp(),
            'gameId' => $this->getGameId(),
        ];
    }
}