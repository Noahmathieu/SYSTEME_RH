
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'prenom' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'telephone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'poste' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'salaire' => [
                'type'       => 'DECIMAL',
                'constraint' => [10, 2],
            ],
            'date_embauche' => [
                'type'       => 'DATE',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('employes');
    }