<?php

namespace Creasi\Base\Enums;

enum StakeholderStatus: int
{
    use KeyableEnum;

    /**
     * Whether the stakeholder has permanent relationship with the company.
     *
     * It commonly means that the stakeholder is a part of the company, including
     * the company's board, subsidiary companies, shareholders, employees, etc.
     */
    case Permanent = 1;

    /**
     * Whether the stakeholder has some form of contracts relationship with the company.
     *
     * It commonly means that the stakeholder is a contractor, vendor, supplier,
     * freelancer, or customer of the company.
     */
    case Contract = 2;

    /**
     * Whether the stakeholder still under certain circumstances before they're be able
     * to join the company.
     *
     * It commonly means that the stakeholder is a potential employee, probationer,
     * internshiper, or it could be a contractor, vendor, supplier, or customer that
     * haven't had any contract yet.
     */
    case Candidate = 3;

    public function isPermanent(): bool
    {
        return $this === self::Permanent;
    }

    public function isContract(): bool
    {
        return $this === self::Contract;
    }

    public function isCandidate(): bool
    {
        return $this === self::Candidate;
    }
}
