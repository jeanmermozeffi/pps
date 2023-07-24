<?php
namespace PS\GestionBundle\Service;

use APY\DataGridBundle\Grid\Action\RowAction as DataGridRowAction;

class RowAction extends DataGridRowAction
{


    public $ajax = true;

     /** @var bool */
    protected $controller;



    /**
     * @var array
     */
    private $iconsMap = [
        'supprimer'        => ['icon' => 'fa fa-remove', 'class' => 'btn-danger'],
        'ressoumission'    => ['icon' => 'fa fa-mail-reply', 'class' => 'yellow'],
        'détails'          => ['icon' => 'fa fa-list', 'class' => 'btn-default'],
        'désactiver'       => ['icon' => 'fa fa-ban', 'class' => 'btn-warning'],
        'historique'       => ['icon' => 'fa fa-file', 'class' => 'dark'],
        'voir'             => ['icon' => 'fa fa-eye', 'class' => 'default'],
        'aperçu'           => ['icon' => 'fa fa-eye', 'class' => 'default'],
        'ajouter'          => ['icon' => 'fa fa-plus', 'class' => 'default'],
        'nouveau'          => ['icon' => 'fa fa-plus', 'class' => 'default'],
        'nouvelle'         => ['icon' => 'fa fa-plus', 'class' => 'default'],
        'modifier'         => ['icon' => 'fa fa-edit', 'class' => 'btn-info'],
        'valider'          => ['icon' => 'fa fa-check', 'class' => 'default'],
        'imprimer'         => ['icon' => 'fa fa-print', 'class' => 'btn-warning'],
        'traiter'          => ['icon' => 'fa fa-check', 'class' => 'default'],
        'compte rendu'     => ['icon' => 'fa fa-file', 'class' => 'default'],
        'fichier'          => ['icon' => 'fa fa-file-pdf-o', 'class' => 'blue'],
        'envoyer par mail' => ['icon' => 'fa fa-envelope', 'class' => 'default'],
        'email' => ['icon' => 'fa fa-at', 'class' => 'default'],
        'lier' => ['icon' => 'fa fa-link', 'class' => 'green']
    ];

    /**
     * @param $action
     */
    public function getIcon($action)
    {
        $action = mb_strtolower($action);
        

        if (!isset($this->iconsMap[$action])) {
            $action = explode(' ', $action)[0];
        }

        return isset($this->iconsMap[$action]) ? $this->iconsMap[$action] : false;
    }


    



     /**
     * Render action for row.
     *
     * @param Row $row
     *
     * @return RowAction|null
     */
    public function render($row)
    {
       foreach ((array)$this->callbacks as $callback) {

            if (is_callable($callback)) {
                $result = call_user_func($callback, $this, $row);
                if (null === $result) {
                    return;
                }

                if (is_array($result) && isset($result['controller'])) {
                    return $result;
                }
            }
        }

        return $this;
    }


}
