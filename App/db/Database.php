<?php
class Database {
    private $conn;
    private string $local = 'localhost'; // Endereço do servidor MySQL
    private string $db = 'servico';     // Nome do banco de dados (atualizado para 'servico')
    private string $user = 'root';      // Usuário do banco de dados
    private string $password = '';      // Senha do banco de dados
    private string $table;

    function __construct(string $tabela) {
        $this->table = $tabela; // Define a tabela a ser usada
        $this->conecta();       // Conecta ao banco de dados
    }

    // Método público para acessar a conexão
    public function getConnection() {
        return $this->conn;
    }

    // Método privado para conectar ao banco de dados
    private function conecta() {
        try {
            $this->conn = new PDO("mysql:host=".$this->local.";dbname=".$this->db, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $err) {
            die("Connection Failed: " . $err->getMessage());
        }
    }

    // Função para inserir dados
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute($data);
            return true;
        } catch(PDOException $e) {
            throw new Exception("Erro ao inserir dados: " . $e->getMessage());
        }
    }

    // Função para buscar usuário por email
    public function buscarUsuarioPorEmail($email) {
        $sql = $this->conn->prepare("SELECT * FROM $this->table WHERE email = :email");
        $sql->bindValue(":email", $email);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // Função para listar clientes
    public function listarClientes() {
        $sql = $this->conn->prepare("SELECT id, nome, email, telefone FROM cliente");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para buscar cliente por ID
    public function getClienteById($cliente_id) {
        $query = "SELECT id, nome, email, telefone FROM cliente WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cliente_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para adicionar um cliente
    public function adicionarCliente($nome, $email, $telefone, $senha) {
        if (empty($nome) || empty($email) || empty($senha)) {
            throw new InvalidArgumentException("Dados inválidos para adicionar cliente.");
        }

        // Criptografa a senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = $this->conn->prepare("INSERT INTO cliente (nome, email, telefone, senha) VALUES (?, ?, ?, ?)");
        $sql->execute([$nome, $email, $telefone, $senhaHash]);
    }

    // Função para excluir um cliente
    public function excluirCliente($id_cliente) {
        try {
            $sql = $this->conn->prepare("DELETE FROM cliente WHERE id = ?");
            $sql->execute([$id_cliente]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir cliente: " . $e->getMessage());
        }
    }

    // Função para editar um cliente
    public function editarCliente($id_cliente, $nome, $email, $telefone, $senha = null) {
        if (empty($id_cliente) || empty($nome) || empty($email)) {
            throw new InvalidArgumentException("Dados inválidos para editar cliente.");
        }

        $params = [$nome, $email, $telefone, $id_cliente];
        $query = "UPDATE cliente SET nome = ?, email = ?, telefone = ?";

        if ($senha) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $query .= ", senha = ?";
            $params[] = $senhaHash;
        }

        $query .= " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
    }

    // Função para listar serviços
    public function listarServicos() {
        $sql = $this->conn->prepare("SELECT * FROM servicos");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para buscar serviço por ID
    public function getServicoById($servico_id) {
        $query = "SELECT * FROM servicos WHERE id_servico = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$servico_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para solicitar serviço
    public function solicitarServico($cliente_id, $id_servico) {
        if (empty($cliente_id) || empty($id_servico)) {
            throw new Exception("Dados inválidos para solicitação.");
        }

        // Verifica se o cliente existe
        $cliente = $this->getClienteById($cliente_id);
        if (!$cliente) {
            throw new Exception("Cliente não encontrado.");
        }

        // Verifica se o serviço existe
        $servico = $this->getServicoById($id_servico);
        if (!$servico) {
            throw new Exception("Serviço não encontrado.");
        }

        // Insere a solicitação
        $query = "INSERT INTO solicitacoes (id_cliente, id_servico) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cliente_id, $id_servico]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("Falha ao registrar solicitação.");
        }
    }

    // Função para listar solicitações
    public function listarSolicitacoes() {
        $query = "
            SELECT 
                c.id AS cliente_id,
                c.nome AS cliente_nome,
                c.email AS cliente_email,
                c.telefone AS cliente_telefone,
                s.id_servico AS servico_id,
                s.nome AS servico_nome,
                s.descricao AS servico_descricao,
                s.preco AS servico_preco,
                sol.id AS solicitacao_id,
                sol.data_solicitacao
            FROM solicitacoes sol
            INNER JOIN cliente c ON sol.id_cliente = c.id
            INNER JOIN servicos s ON sol.id_servico = s.id_servico
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return is_array($result) ? $result : [];
    }

    // Fecha a conexão ao destruir o objeto
    public function __destruct() {
        $this->conn = null;
    }
}
?>