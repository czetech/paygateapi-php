<?php
namespace PayGateApi\Objects;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

final class ResultCreateReport
{

    /**
     *
     * @SerializedName("report-id")
     * @Type("integer")
     *
     * @var int $reportId
     */
    private $reportId;

    public function getReportId(): int
    {
        return $this->reportId;
    }
}
