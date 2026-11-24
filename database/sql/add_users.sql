-- Add a `role` column to users table (student or tutor)
ALTER TABLE `users` 
    ADD COLUMN `role` VARCHAR(20) NOT NULL DEFAULT 'student';

-- Example inserts with bcrypt password for 'password'
-- (the password hash below corresponds to the plaintext 'password')
INSERT INTO `users` (`name`, `email`, `password`, `created_at`, `updated_at`, `role`) VALUES
('Tutor One','tutor1@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.6t1GQw52z0W/Zb2',NOW(),NOW(),'tutor'),
('Student One','student1@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.6t1GQw52z0W/Zb2',NOW(),NOW(),'student');

-- Note: If your Laravel User model uses the `password` cast to 'hashed', you can also create users
-- using tinker or a small seeder so Laravel hashes the password automatically.
