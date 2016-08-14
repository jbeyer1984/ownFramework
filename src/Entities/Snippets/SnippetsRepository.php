<?php

namespace MyApp\src\Entities\Snippets;

use MyApp\src\Tasks\Interfaces\ResetInterface;
use MyApp\src\Tasks\Tasks;
use MyApp\src\Utility\Db;

class SnippetsRepository extends Tasks implements ResetInterface
{

  /**
   * @var Db
   */
  private $db;

  public function __construct()
  {
  }

  /**
   * @return $this
   */
  public function init()
  {
    parent::__construct();
    $this->db = $this->components->get('db');
    
    return $this;
  }

  public function reset()
  {

  }

  /**
   * @return array
   */
  public function getSnippetsData()
  {
    $sql = <<< SQL
SELECT groups.eval_group_id, groups.name eval_group_name,
  templates.eval_template_id, templates.name eval_template_name, templates.snippet eval_template_snippet
FROM eval_snippet snippets
JOIN eval_group groups USING(eval_group_id)
JOIN eval_template templates USING(eval_template_id)
SQL;

    $result = $this->db->execute($sql, array())->getData();

    return $result;
  }
}