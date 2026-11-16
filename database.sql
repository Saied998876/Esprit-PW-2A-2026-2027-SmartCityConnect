CREATE TABLE campaigns (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    category TEXT NOT NULL,
    goal_cents INTEGER NOT NULL,
    status TEXT NOT NULL DEFAULT 'active',
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE donations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    campaign_id INTEGER NOT NULL,
    donor_name TEXT NOT NULL,
    donor_email TEXT NOT NULL,
    amount_cents INTEGER NOT NULL,
    message TEXT NOT NULL DEFAULT '',
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL COLLATE NOCASE UNIQUE,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'citizen'
        CHECK (role IN ('citizen', 'admin')),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO campaigns (title, description, category, goal_cents) VALUES
('A greener street, block by block',
 'Plant shade trees and native gardens along busy walking routes, bringing cooler streets and more welcoming public spaces to the neighbourhood.',
 'green', 850000),
('Solar lights for safer evenings',
 'Install efficient solar lighting near community paths and transit stops so residents can get around more comfortably after dark.',
 'energy', 1200000),
('A smarter community garden',
 'Equip a shared garden with water-saving tools and simple sensors that help volunteers care for plants while using fewer resources.',
 'community', 600000);
