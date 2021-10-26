<?php
namespace PayGateApi\Objects;

final class ReportResultObject
{

    /**
     *
     * @var bool $ready
     */
    private $ready;

    /**
     *
     * @var ResultReport[]|null $resultReportList
     */
    private $resultReportList;

    /**
     *
     * @var int|null $tryAgain
     */
    private $tryAgain;

    public function __construct(bool $ready, ?array $resultReportList, ?int $tryAgain)
    {
        $this->ready = $ready;
        $this->resultReportList = $resultReportList;
        $this->tryAgain = $tryAgain;
    }

    public function getReady(): ?bool
    {
        return $this->ready;
    }

    public function getResultReportList(): ?array
    {
        return $this->resultReportList;
    }

    public function getTryAgain(): ?int
    {
        return $this->tryAgain;
    }
}
