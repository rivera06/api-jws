<?php
class estados
{
  private $conn;
  private $table_name = "estados";
  /**
   * IdEstado
   * Nombre
   * Descripcion
   * IdEstadoAnterior
   * IdEstadoSiguiente
   */
  public $IdEstado;
  public $Descripcion;
  public $Nombre;
  public $IdEstadoAnterior;
  public $IdEstadoSiguientes;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  function readAll()
  {
    $query = "SELECT 
                  um.IdEstado, um.Nombre, um.Descripcion, um.IdEstadoAnterior, um.IdEstadoSiguiente
                FROM
                  " . $this->table_name . " um
                ORDER BY
                  um.FechaCreacion DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  function create()
  {
    $query = "INSERT INTO " . $this->table_name . "
              SET
                Descripcion = :Descripcion,
                Nombre = :Nombre,
                IdEstadoAnterior = :IdEstadoAnterior,
                IdEstadoSiguiente = :IdEstadoSiguiente";
    $stmt = $this->conn->prepare($query);
    $this->Descripcion = htmlspecialchars(strip_tags($this->Descripcion));
    $this->Nombre = htmlspecialchars(strip_tags($this->Nombre));
    $this->IdEstadoAnterior = htmlspecialchars(strip_tags($this->IdEstadoAnterior));
    $this->IdEstadoSiguiente = htmlspecialchars(strip_tags($this->IdEstadoSiguiente));

    $stmt->bindParam(':Descripcion', $this->Descripcion);
    $stmt->bindParam(':Nombre', $this->Nombre);
    $stmt->bindParam(':IdEstadoAnterior', $this->IdEstadoAnterior);
    $stmt->bindParam(':IdEstadoSiguiente', $this->IdEstadoSiguiente);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  function update()
  {
    $query = "UPDATE
                " . $this->table_name . "
              SET
                Nombre=:Nombre,
                Descripcion=:Descripcion,
                IdEstadoAnterior=:IdEstadoAnterior,
                IdEstadoSiguiente=:IdEstadoSiguiente
              WHERE
                IdEstado=:IdEstado";
    $stmt = $this->conn->prepare($query);

    $this->Nombre = htmlspecialchars(strip_tags($this->Nombre));
    $this->Descripcion = htmlspecialchars(strip_tags($this->Descripcion));
    $this->IdEstadoAnterior = htmlspecialchars(strip_tags($this->IdEstadoAnterior));
    $this->IdEstadoSiguiente = htmlspecialchars(strip_tags($this->IdEstadoSiguiente));
    $this->IdEstado = htmlspecialchars(strip_tags($this->IdEstado));

    $stmt->bindParam(':Nombre', $this->Nombre);
    $stmt->bindParam(':Descripcion', $this->Descripcion);
    $stmt->bindParam(':IdEstado', $this->IdEstado);
    $stmt->bindParam(':IdEstadoSiguiente', $this->IdEstadoSiguiente);
    $stmt->bindParam(':IdEstadoAnterior', $this->IdEstadoAnterior);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
  function delete()
  {
    $query = "DELETE FROM " . $this->table_name . " WHERE IdEstado = ?";
    $stmt = $this->conn->prepare($query);

    $this->IdEstado = htmlspecialchars(strip_tags($this->IdEstado));

    $stmt->bindParam(1, $this->IdEstado);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  /*function changeState()
  {
    $query = "UPDATE
                " . $this->table_name . "
              SET
                Estado=:Estado
              WHERE
                IdEstado=:IdEstado";
    $stmt = $this->conn->prepare($query);

    $this->Estado = htmlspecialchars(strip_tags($this->Estado));
    $this->IdEstado = htmlspecialchars(strip_tags($this->IdEstado));

    $stmt->bindParam(':Estado', $this->Estado);
    $stmt->bindParam(':IdEstado', $this->IdEstado);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }*/
}
