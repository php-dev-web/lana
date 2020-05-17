<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%enrollment}}`.
 */
class m200516_112758_create_enrollment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%enrollment}}', [
            'id' => $this->primaryKey(),
            // 'date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'date' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'sum' => $this->string()->notNull(),
            'action' => $this->string()->notNull()
        ]);

        $this->addForeignKey(
            'enrollment_id',  
            'enrollment', 
            'user_id',
            'user', 
            'id', 
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%enrollment}}');

        $this->dropForeignKey(
            'enrollment_id',
            'enrollment'
        );
    }
}
