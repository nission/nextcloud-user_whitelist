<?php

namespace OCA\UserWhitelist\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version001Date20200917112639 extends SimpleMigrationStep
{
  /**
   * @param IOutput $output
   * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
   * @param array $options
   * @return null|ISchemaWrapper
   */
  public function changeSchema(IOutput $output, Closure $schemaClosure, array $options)
  {
    /** @var ISchemaWrapper $schema */
    $schema = $schemaClosure();

    if (!$schema->hasTable('user_whitelist')) {
      $table = $schema->createTable('user_whitelist');
      $table->addColumn('id', 'integer', [
        'autoincrement' => true,
        'notnull' => true,
      ]);
      $table->addColumn('name', 'string', [
        'notnull' => true,
        'length' => 32,
        'default' => '',
        'comment' => '用户名称',
      ]);
      $table->addColumn('remark', 'string', [
        'notnull' => true,
        'length' => 64,
        'default' => '',
        'comment' => '备注',
      ]);
      $table->addColumn('status', 'integer', [
        'notnull' => true,
        'default' => 1,
        'comment' => '状态：1:临时添加，2:无效，3:有效',
      ]);
      $table->addColumn('create', 'datetime', [
        'notnull' => true,
        'default' => '1000-01-01 00:00:00.000000',
        'comment' => '添加时间',
      ]);
      $table->addColumn('create_user', 'string', [
        'notnull' => true,
        'default' => '',
        'comment' => '添加人员',
      ]);
      $table->addColumn('edit', 'datetime', [
        'notnull' => true,
        'default' => '1000-01-01 00:00:00.000000',
        'comment' => '更新时间',
      ]);
      $table->addColumn('edit_user', 'string', [
        'notnull' => true,
        'default' => '',
        'comment' => '最后更新人员',
      ]);

      $table->setPrimaryKey(['id']);
      $table->addUniqueIndex(['name'], 'user_name_un_index');
    }
    return $schema;
  }
}
