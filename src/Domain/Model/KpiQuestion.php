<?php

namespace Domain\Model;

class KpiQuestion
{
    private $id;
    private $questionId;
    private $nbApparitionsTotal;
    private $nbReponsesCorrectes;
    private $tempsMoyenReponseBonne;
    private $nbReponsesFausses;
    private $tempsMoyenReponseFausses;
    private $cooccurrencesQuestions = [];
    private $tauxReussite;

    public function __construct(
        $id,
        $questionId,
        int $nbApparitionsTotal = 0,
        int $nbReponsesCorrectes = 0,
        float $tempsMoyenReponseBonne = 0.0,
        int $nbReponsesFausses = 0,
        float $tempsMoyenReponseFausses = 0.0,
        array $cooccurrencesQuestions = [],
        float $tauxReussite = 0.0
    ) {
        $this->id = $id;
        $this->questionId = $questionId;
        $this->nbApparitionsTotal = $nbApparitionsTotal;
        $this->nbReponsesCorrectes = $nbReponsesCorrectes;
        $this->tempsMoyenReponseBonne = $tempsMoyenReponseBonne;
        $this->nbReponsesFausses = $nbReponsesFausses;
        $this->tempsMoyenReponseFausses = $tempsMoyenReponseFausses;
        $this->cooccurrencesQuestions = $cooccurrencesQuestions;
        $this->tauxReussite = $tauxReussite;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }

    public function getNbApparitionsTotal(): int
    {
        return $this->nbApparitionsTotal;
    }

    public function getNbReponsesCorrectes(): int
    {
        return $this->nbReponsesCorrectes;
    }

    public function getTempsMoyenReponseBonne(): float
    {
        return $this->tempsMoyenReponseBonne;
    }

    public function getNbReponsesFausses(): int
    {
        return $this->nbReponsesFausses;
    }

    public function getTempsMoyenReponseFausses(): float
    {
        return $this->tempsMoyenReponseFausses;
    }

    public function getCooccurrencesQuestions(): array
    {
        return $this->cooccurrencesQuestions;
    }

    public function getTauxReussite(): float
    {
        return $this->tauxReussite;
    }
}
