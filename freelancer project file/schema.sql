-- TWINEX database schema
-- Import this via hPanel -> Databases -> phpMyAdmin -> your database -> Import

CREATE TABLE IF NOT EXISTS clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(50) DEFAULT '',
  status VARCHAR(30) DEFAULT 'new',
  added_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS team_members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  role VARCHAR(120) DEFAULT '',
  email VARCHAR(255) DEFAULT '',
  phone VARCHAR(50) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  client VARCHAR(255) DEFAULT '',
  status VARCHAR(30) DEFAULT 'new',
  budget DECIMAL(12,2) DEFAULT 0,
  paid DECIMAL(12,2) DEFAULT 0,
  expenses DECIMAL(12,2) DEFAULT 0,
  deadline VARCHAR(50) DEFAULT NULL,
  member_names TEXT DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(20) NOT NULL,
  title VARCHAR(255) DEFAULT '',
  client VARCHAR(255) DEFAULT NULL,
  project VARCHAR(255) DEFAULT NULL,
  amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  date VARCHAR(50) DEFAULT '',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS invoices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  number VARCHAR(30) NOT NULL,
  client VARCHAR(255) DEFAULT '',
  project VARCHAR(255) DEFAULT NULL,
  amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  due_date VARCHAR(50) DEFAULT '',
  notes TEXT DEFAULT NULL,
  status VARCHAR(20) DEFAULT 'draft',
  created_date VARCHAR(50) DEFAULT '',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  project VARCHAR(255) DEFAULT NULL,
  assignee VARCHAR(255) DEFAULT NULL,
  status VARCHAR(20) DEFAULT 'assigned',
  deadline_date VARCHAR(30) DEFAULT NULL,
  deadline_time VARCHAR(20) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Starter data so the app isn't empty on first load (optional — delete these
-- INSERTs if you'd rather start with a completely blank workspace)

INSERT INTO clients (name, phone, status) VALUES
  ('Twinex Media', '7995107520', 'active'),
  ('Solstice Interiors', '9876543210', 'active'),
  ('Vantage Fitness', '9123456780', 'active'),
  ('Harbor & Co.', '9988776655', 'new');

INSERT INTO team_members (name, role, email, phone) VALUES
  ('Riya Sharma', 'Designer', 'riya@twinex.co.in', '9000011111'),
  ('Aman Verma', 'Developer', 'aman@twinex.co.in', '9000022222'),
  ('Zoya Khan', 'Marketing', 'zoya@twinex.co.in', '9000033333');

INSERT INTO projects (name, client, status, budget, paid, expenses, deadline, member_names) VALUES
  ('Leads Generation', 'Twinex Media', 'new', 9999.99, 0, 0, NULL, '[]'),
  ('Brand Website Revamp', 'Solstice Interiors', 'ongoing', 45000, 20000, 6000, '28 Jul', '["Riya Sharma","Aman Verma"]'),
  ('Instagram Ad Campaign', 'Solstice Interiors', 'ongoing', 15000, 15000, 3000, '20 Jul', '["Riya Sharma"]'),
  ('App Store Launch Kit', 'Vantage Fitness', 'completed', 32000, 32000, 9000, '02 Jul', '["Aman Verma","Riya Sharma","Zoya Khan"]'),
  ('Logo & Brand Identity', 'Harbor & Co.', 'new', 18000, 0, 0, NULL, '[]');

INSERT INTO payments (type, title, client, project, amount, date) VALUES
  ('incoming', 'Milestone 1 payment', 'Solstice Interiors', 'Brand Website Revamp', 20000, '10 Jul 2026'),
  ('incoming', 'Full payment - Ad Campaign', 'Solstice Interiors', 'Instagram Ad Campaign', 15000, '05 Jul 2026'),
  ('incoming', 'Final payment - Launch Kit', 'Vantage Fitness', 'App Store Launch Kit', 32000, '01 Jul 2026'),
  ('outgoing', 'Designer payout - July', NULL, 'Brand Website Revamp', 6000, '11 Jul 2026'),
  ('outgoing', 'Meta Ads budget', NULL, 'Instagram Ad Campaign', 3000, '06 Jul 2026'),
  ('outgoing', 'Contractor payout - Launch Kit', NULL, 'App Store Launch Kit', 9000, '02 Jul 2026');
