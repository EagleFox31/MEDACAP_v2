<?php

namespace Application\Service;

use Domain\Repository\KpiQuestionRepositoryInterface;
use Domain\Repository\CommercialQuestionRepositoryInterface;
use Domain\Model\KpiQuestion;

class KpiUpdaterService
{
    private $kpiQuestionRepository;
    private $questionRepository;

    public function __construct(
        KpiQuestionRepositoryInterface $kpiQuestionRepository,
        CommercialQuestionRepositoryInterface $questionRepository
    ) {
        $this->kpiQuestionRepository = $kpiQuestionRepository;
        $this->questionRepository = $questionRepository;
    }

    public function recordQuestionStats($questionId, bool $correct, int $time): void
    {
        $kpi = $this->kpiQuestionRepository->findByQuestion($questionId);
        if (!$kpi) {
            $kpi = new KpiQuestion(null, $questionId);
        }
        // simple counter update
        $total = $kpi->getNbApparitionsTotal() + 1;
        $correctCount = $kpi->getNbReponsesCorrectes() + ($correct ? 1 : 0);
        $wrongCount = $kpi->getNbReponsesFausses() + ($correct ? 0 : 1);
        $taux = $correctCount / $total * 100;
        $kpi = new KpiQuestion(
            $kpi->getId(),
            $questionId,
            $total,
            $correctCount,
            $kpi->getTempsMoyenReponseBonne(),
            $wrongCount,
            $kpi->getTempsMoyenReponseFausses(),
            $kpi->getCooccurrencesQuestions(),
            $taux
        );
        $this->kpiQuestionRepository->save($kpi);
    }
}
