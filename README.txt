"# Fullstack_backend_php" 
CAR PARTS MANAGER

ALUNO(S)
António Silva Rodrigues


O projeto tem duas partes, cada uma no seu repositório:
- PHP (vendas e páginas)
- Node.js (compras / API)

FONTE DE DADOS

Duas bases de dados MySQL: sales_db (clientes, peças, vendas, tipos de pagamento) e procurement_db (fornecedores, encomendas, utilizadores).

DESCRIÇÃO
Sistema de gestão de peças de automóvel. A parte de vendas (PHP + MySQL) trata do catálogo, clientes e vendas. A parte de compras (Node.js + MySQL) e uma API REST para fornecedores e encomendas a fornecedores. As páginas PHP comunicam com a API Node por HTTP.

REQUISITOS
- XAMPP com Apache e MySQL ligados
- Node.js e Git

COMO CORRER
1. Clonar os dois repositórios: 
cd C:/xampp/htdocs/Fullstack_backend_php git clone [repositório PHP] Fullstack_backend_php
   O repositório PHP tem de ficar nesta pasta e com este nome, porque o nome faz parte dos endereços usados no código.
   O repositório Node pode ser clonado em qualquer pasta:
       git clone [repositório Node]
2. Bases de dados: no phpMyAdmin, separador Import, importar
       Fullstack_backend_php/database/sales_db.sql
       (repositório Node)/database/procurement_db.sql
3. Serviço Node.js, num terminal:
       cd procurement_backend
       copy .env
       npm install
       npm start
   (se o MySQL tiver password, escrevê-la em DB_PASS no ficheiro .env
   e em Fullstack_backend_php/Sales_backend/db.php)
   O serviço tem de ficar a correr, e fica em http://localhost:3001
4. Abrir http://localhost/Fullstack_backend_php/Presentation/

Utilizador de teste do serviço Node: carlos@admin.com / admin1234 e 'test@admin.com'/'$2b$10$J2jFhBE6emTP1NHk0VFjWeofw
K8GwfqELZ.6Qb4LcAVD1v.7SpLUi'

FUNCIONALIDADES
A) Catálogo de peças com filtros por marca, tipo de peça e gama de preço (Presentation/materials.php)
B) Formulário de venda a cliente com dropdown de clientes, informação da peça e tipo de pagamento. Os tipos de pagamento ficam na base de dados e podem ser acrescentados em Presentation/payment_types.php
C) Lista de fornecedores e submissão de encomendas através da API Node.js (suppliers.php e new_order.php). O Node valida cada referência de peça contra o catálogo PHP antes de gravar a encomenda.
Autenticação (JWT, perfis admin/employee) no serviço Node.js.