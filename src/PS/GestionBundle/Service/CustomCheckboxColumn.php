<?php

namespace PS\GestionBundle\Service;

use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Row;

class CustomCheckboxColumn extends Column
{
    protected $selectField;

    public function __construct(array $params = [])
    {
        parent::__construct($params);

        $this->setInputType('checkbox');
        $this->setTitle($params['title'] ?? 'Checkbox');
        $this->selectField = $params['select_field'] ?? 'id'; // Champ à utiliser pour la valeur de la case à cocher
    }

    public function renderCellContent(Row $row, $value, $router)
    {
        $rowId = $row->getPrimaryFieldValue();
        
        $checkboxHtml = sprintf(
            '<label class="">
                 <div class="icheckbox_flat-green checked" aria-checked="true" aria-disabled="false" style="position: relative;">
                        <input type="checkbox" class="flat-red profile-checkbox" checked="" value="%s" name="%s[]" style="position: absolute; opacity: 0;">
                </div>
            </label>',
            // '<input type="checkbox" class="profile-checkbox" value="%s" name="%s[]">',
            htmlspecialchars($rowId),
            $this->getId()
        );

        return $checkboxHtml;
    }
}