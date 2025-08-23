-- seeds.sql — jeu d'essais initial
USE tpak;

-- Agencies
INSERT INTO agencies (name) VALUES
('Paris'),
('Lyon'),
('Marseille'),
('Toulouse'),
('Nice'),
('Nantes'),
('Strasbourg'),
('Montpellier'),
('Bordeaux'),
('Lille'),
('Rennes'),
('Reims')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Users (importés du SIRH). Mot de passe par défaut: 'password'

INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES
('Alexandre','Martin','alexandre.martin@email.fr','0612345678','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin'),
('Sophie','Dubois','sophie.dubois@email.fr','0698765432','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Julien','Bernard','julien.bernard@email.fr','0622446688','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Camille','Moreau','camille.moreau@email.fr','0611223344','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Lucie','Lefèvre','lucie.lefevre@email.fr','0777889900','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Thomas','Leroy','thomas.leroy@email.fr','0655443322','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Chloé','Roux','chloe.roux@email.fr','0633221199','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Maxime','Petit','maxime.petit@email.fr','0766778899','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Laura','Garnier','laura.garnier@email.fr','0688776655','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Antoine','Dupuis','antoine.dupuis@email.fr','0744556677','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Emma','Lefebvre','emma.lefebvre@email.fr','0699887766','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Louis','Fontaine','louis.fontaine@email.fr','0655667788','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Clara','Chevalier','clara.chevalier@email.fr','0788990011','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Nicolas','Robin','nicolas.robin@email.fr','0644332211','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Marine','Gauthier','marine.gauthier@email.fr','0677889922','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Pierre','Fournier','pierre.fournier@email.fr','0722334455','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Sarah','Girard','sarah.girard@email.fr','0688665544','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Hugo','Lambert','hugo.lambert@email.fr','0611223366','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Julie','Masson','julie.masson@email.fr','0733445566','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Arthur','Henry','arthur.henry@email.fr','0666554433','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user');
