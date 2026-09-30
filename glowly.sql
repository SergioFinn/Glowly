-- Glowly: actualización aditiva. No elimina productos, clientes ni pedidos.
USE escolar_glowly;
-- InnoDB permite que el pedido, detalle y stock participen en una transacción real.
ALTER TABLE categorias ENGINE=InnoDB;
ALTER TABLE clientes ENGINE=InnoDB;
ALTER TABLE productos ENGINE=InnoDB;
ALTER TABLE variantes ENGINE=InnoDB;
ALTER TABLE pedidos ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS detalle_pedido (
 id INT AUTO_INCREMENT PRIMARY KEY,
 pedido_id INT NOT NULL,
 variante_id INT NOT NULL,
 nombre_producto VARCHAR(200) NOT NULL,
 cantidad INT NOT NULL CHECK (cantidad BETWEEN 1 AND 999),
 precio_unitario DECIMAL(10,2) NOT NULL CHECK (precio_unitario>0),
 subtotal DECIMAL(12,2) GENERATED ALWAYS AS (cantidad*precio_unitario) STORED,
 FOREIGN KEY(pedido_id) REFERENCES pedidos(id),
 FOREIGN KEY(variante_id) REFERENCES variantes(id),
 UNIQUE KEY uq_pedido_variante(pedido_id,variante_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS asesorias (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(120) NOT NULL,
 correo VARCHAR(190) NOT NULL,
 colorimetria ENUM('frios','calidos','neutros','no_se') NOT NULL,
 evento ENUM('casual','oficina','noche') NOT NULL,
 comentarios VARCHAR(500) NULL,
 acepta_politicas BOOLEAN NOT NULL CHECK (acepta_politicas=1),
 estado ENUM('pendiente','atendida','cancelada') NOT NULL DEFAULT 'pendiente',
 creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
