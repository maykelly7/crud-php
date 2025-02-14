<?php
class Database {
    private $conn;
    private string $local = 'localhost'; //10.28.0.155
    private string $db = 'servicos'; 
    private string $user = 'root'; //devweb
    private string $password = '';
    private string $table;

    function __construct(string $tabela) {
        $this->table = $tabela; // Garante que $table seja uma string
        $this->conecta();
    }

    // Método público para acessar a conexão
    public function getConnection() {
        return $this->conn;
    }

    private function conecta() {
        try {
            $this->conn = new PDO("mysql:host=".$this->local.";dbname=".$this->db, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $err) {
            die("Conection Failed: " . $err->getMessage());
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

    // Função para listar serviços
    public function listarServicos() {
        $sql = $this->conn->prepare("SELECT * FROM servicos");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para adicionar um serviço
    public function adicionarServico($nome, $descricao, $preco) {
        if (empty($nome) || empty($descricao) || !is_numeric($preco)) {
            throw new InvalidArgumentException("Dados inválidos para adicionar serviço.");
        }

        $sql = $this->conn->prepare("INSERT INTO servicos (nome, descricao, preco) VALUES (?, ?, ?)");
        $sql->execute([$nome, $descricao, $preco]);
    }

    // Função para excluir um serviço
    public function excluirServico($id_servico) {
        try {
            $sql = $this->conn->prepare("DELETE FROM servicos WHERE id_servico = ?");
            $sql->execute([$id_servico]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir serviço: " . $e->getMessage());
        }
    }

    // Função para buscar um serviço por ID
    public function buscarServicoPorId($id_servico) {
        $sql = $this->conn->prepare("SELECT * FROM servicos WHERE id_servico = ?");
        $sql->execute([$id_servico]);
        $servico = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$servico) {
            throw new Exception("Serviço não encontrado.");
        }

        return $servico;
    }

    // Função para editar um serviço
    public function editarServico($id_servico, $nome, $descricao, $preco) {
        if (empty($id_servico) || empty($nome) || empty($descricao) || !is_numeric($preco)) {
            throw new InvalidArgumentException("Dados inválidos para editar serviço.");
        }

        $sql = $this->conn->prepare("UPDATE servicos SET nome = ?, descricao = ?, preco = ? WHERE id_servico = ?");
        $sql->execute([$nome, $descricao, $preco, $id_servico]);
    }

    // Fecha a conexão ao destruir o objeto
    public function __destruct() {
        $this->conn = null;
    }

    public function listarClientes() {
        $sql = $this->conn->prepare("SELECT id_cliente, nome, email, telefone FROM cliente");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarCliente($nome, $email, $telefone, $senha) {
        if (empty($nome) || empty($email) || empty($senha)) {
            throw new InvalidArgumentException("Dados inválidos para adicionar cliente.");
        }
    
        // Criptografa a senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    
        $sql = $this->conn->prepare("INSERT INTO cliente (nome, email, telefone, senha) VALUES (?, ?, ?, ?)");
        $sql->execute([$nome, $email, $telefone, $senhaHash]);
    }

    public function buscarClientePorId($id_cliente) {
        $sql = $this->conn->prepare("SELECT id_cliente, nome, email, telefone FROM cliente WHERE id_cliente = ?");
        $sql->execute([$id_cliente]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function excluirCliente($id_cliente) {
        try {
            $sql = $this->conn->prepare("DELETE FROM cliente WHERE id_cliente = ?");
            $sql->execute([$id_cliente]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir cliente: " . $e->getMessage());
        }
    }

    public function editarCliente($id_cliente, $nome, $email, $telefone, $senha = null) {
        if (empty($id_cliente) || empty($nome) || empty($email)) {
            throw new InvalidArgumentException("Dados inválidos para editar cliente.");
        }
    
        // Se uma nova senha for fornecida, criptografa-a
        if ($senha) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = $this->conn->prepare("UPDATE cliente SET nome = ?, email = ?, telefone = ?, senha = ? WHERE id_cliente = ?");
            $sql->execute([$nome, $email, $telefone, $senhaHash, $id_cliente]);
        } else {
            $sql = $this->conn->prepare("UPDATE cliente SET nome = ?, email = ?, telefone = ? WHERE id_cliente = ?");
            $sql->execute([$nome, $email, $telefone, $id_cliente]);
        }
    }


    
    public function solicitarServico($cliente_id, $id_servico) {
    if (empty($cliente_id) || empty($id_servico)) {
        throw new Exception("Dados inválidos para solicitação.");
    }
    
    // SQL para adicionar a solicitação
    $query = "INSERT INTO solicitacoes (cliente_id, servico_id) VALUES (?, ?)";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$cliente_id, $id_servico]);
    
    if ($stmt->rowCount() === 0) {
        throw new Exception("Falha ao registrar solicitação.");
    }

    
}
    
    public function listarSolicitacoes() {
        $query = "SELECT * FROM solicitacoes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClienteById($cliente_id) {
        $query = "SELECT nome FROM clientes WHERE id_cliente = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cliente_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getServicoById($servico_id) {
        $query = "SELECT nome FROM servicos WHERE id_servico = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$servico_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }






}
?>