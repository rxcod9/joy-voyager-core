<?php

declare(strict_types=1);

namespace Joy\VoyagerCore\Models;

use TCG\Voyager\Models\DataRow;

/**
 * Class VoyagerCoreServiceProvider
 *
 * @category  Package
 * @package   JoyVoyagerCore
 * @author    Ramakant Gangwar <gangwar.ramakant@gmail.com>
 * @copyright 2021 Copyright (c) Ramakant Gangwar (https://github.com/rxcod9)
 * @license   http://github.com/rxcod9/joy-voyager-core/blob/main/LICENSE New BSD License
 * @link      https://github.com/rxcod9/joy-voyager-core
 */
class Via
{
    protected DataRow $row;
    protected $viaIndex = null;
    protected ?Via $via = null;

    public function setOptions(
        DataRow $row,
        $viaIndex = null,
        ?Via $via = null
    ) {
        $this->row      = $row;
        $this->viaIndex = $viaIndex;
        $this->via      = $via;
    }

    /**
     * Get the value of row
     *
     * @return DataRow
     */
    public function getRow(): DataRow
    {
        return $this->row;
    }

    /**
     * Set the value of row
     *
     * @param DataRow $row
     *
     * @return self
     */
    public function setRow(DataRow $row): self
    {
        $this->row = $row;

        return $this;
    }

    /**
     * Get the value of index
     */
    public function getViaIndex()
    {
        return $this->viaIndex;
    }

    /**
     * Set the value of index
     */
    public function setViaIndex($viaIndex): self
    {
        $this->viaIndex = $viaIndex;

        return $this;
    }

    /**
     * Get the value of via
     *
     * @return ?Via
     */
    public function getVia(): ?Via
    {
        return $this->via;
    }

    /**
     * Set the value of via
     *
     * @param ?Via $via
     *
     * @return self
     */
    public function setVia(?Via $via): self
    {
        $this->via = $via;

        return $this;
    }

    /**
     * Get the name prefix
     *
     * @return string
     */
    public function getNamePrefix(): string
    {
        if (!$this->getVia()) {
            if (is_null($this->viaIndex)) {
                return $this->row->field;
            }
            return $this->row->field . '[' . $this->viaIndex . ']';
        }

        if (is_null($this->viaIndex)) {
            return $this->getVia()->getNamePrefix() . '[' . $this->row->field . ']';
        }
        return $this->getVia()->getNamePrefix() . '[' . $this->row->field . '][' . $this->viaIndex . ']';
    }

    /**
     * Get the id prefix
     *
     * @return string
     */
    public function getIdPrefix(): string
    {
        if (!$this->getVia()) {
            if (is_null($this->viaIndex)) {
                return $this->row->field . '_';
            }
            return $this->row->field . '_' . $this->viaIndex . '_';
        }

        if (is_null($this->viaIndex)) {
            return $this->getVia()->getIdPrefix() . '_' . $this->row->field . '_';
        }
        return $this->getVia()->getIdPrefix() . '_' . $this->row->field . '_' . $this->viaIndex . '_';
    }

    /**
     * Get the input key prefix
     *
     * @return string
     */
    public function getInputKeyPrefix(): string
    {
        if (!$this->getVia()) {
            if (is_null($this->viaIndex)) {
                return $this->row->field . '.';
            }
            return $this->row->field . '.' . $this->viaIndex . '.';
        }

        if (is_null($this->viaIndex)) {
            return $this->getVia()->getInputKeyPrefix() . '.' . $this->row->field . '.';
        }
        return $this->getVia()->getInputKeyPrefix() . '.' . $this->row->field . '.' . $this->viaIndex . '.';
    }

    public function toArray()
    {
        return [
            'row'      => $this->getRow(),
            'viaIndex' => $this->getViaIndex(),
            'via'      => optional($this->getVia())->toArray() ?? null,
        ];
    }
}
