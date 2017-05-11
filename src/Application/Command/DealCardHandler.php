<?php declare(strict_types=1);

namespace CardBattleGame\Application\Command;

use CardBattleGame\Domain\Card;
use CardBattleGame\Domain\GameRepository;

class DealCardHandler
{
    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(DealCard $dealCardCommand): void
    {
        $game = $this->gameRepository->get($dealCardCommand->getGameId());

        $card = new Card(
            $dealCardCommand->getType(),
            $dealCardCommand->getHp(),
            $dealCardCommand->getMp()
        );

        $game->dealCardForPlayerOnTurn($card);

        $this->gameRepository->save($game);
    }
}