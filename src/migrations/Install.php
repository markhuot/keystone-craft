<?php

namespace markhuot\keystone\migrations;

use craft\db\Migration;
use markhuot\keystone\db\Table;

class Install extends Migration
{
    public function safeUp()
    {
        $this->createTable(Table::COMPONENT_DATA, [
            'id' => $this->primaryKey(),
            'type' => $this->string(256),
            'data' => $this->json(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createTable(Table::COMPONENTS, [
            'id' => $this->primaryKey(),
            'elementId' => $this->bigInteger()->unsigned(),
            'fieldId' => $this->bigInteger()->unsigned(),
            'dataId' => $this->bigInteger()->unsigned(),
            'sortOrder' => $this->integer(),
            'path' => $this->string(1024),
            'level' => $this->integer(),
            'slot' => $this->string(256),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        return true;
    }

    public function safeDown()
    {
        $this->dropTableIfExists(Table::COMPONENTS);
        $this->dropTableIfExists(Table::COMPONENT_DATA);

        return true;
    }
}
