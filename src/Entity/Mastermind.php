<?php

namespace App\Entity;

interface iMastermind {
    public function __construct(int $taille = 4);
    public function test(string $code): array;
    public function getEssais();
    public function getTaille();
    public function isFini();
}

class Mastermind implements iMastermind
{
    private int $taille;
    private array $codeSecret;
    private array $essais = []; 

    public function __construct(int $taille = 4)
    {
        $this->taille = $taille;
        $this->codeSecret = $this->genererCode();
    }

    private function genererCode(): array
    {
        $digits = range(0, 9);
        shuffle($digits);
        return array_slice($digits, 0, $this->taille);
    }

    public function test(string $proposition): array
    {
        $bienPlaces = 0;
        $malPlaces = 0;

        for ($i = 0; $i < $this->taille; $i++) {
            if ($proposition[$i] == $this->codeSecret[$i]) {
                $bienPlaces++;
            } elseif (in_array($proposition[$i], $this->codeSecret)) {
                $malPlaces++;
            }
        }

        $this->essais[] = ['proposition' => $proposition, 'bienPlaces' => $bienPlaces, 'malPlaces' => $malPlaces];

        return ['bienPlaces' => $bienPlaces, 'malPlaces' => $malPlaces];
    }

    public function getEssais(): array
    {
        return $this->essais;
    }

    public function getTaille(): int
    {
        return $this->taille;
    }

    public function isFini(): bool
    {
        return !empty($this->essais) && end($this->essais)['bienPlaces'] === $this->taille;
    }
}
