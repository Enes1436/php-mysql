<?php
// SISTEM BIBLIOTEKE - Projekt Komplet
// Menaxhimi i librave, huazimeve dhe përdoruesve

session_start();

// ============================================
// HAPI 1: LIDHJA ME DATABAZËN
// ============================================

$host = "localhost";
$user = "root";
$password = "";
$db = "library_db";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("❌ Lidhja dështoi: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
echo "✅ Lidhja me databazën u arrit!<br><br>";

// ============================================
// HAPI 2: KRIJONI TABELAT
// ============================================

// Tabela 1: Kategoritë e Librave
$create_categories = "CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE,
    description TEXT
)";

mysqli_query($conn, $create_categories);
echo "✓ Tabela categories u krijua<br>";

// Tabela 2: Librat
$create_books = "CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    author VARCHAR(100),
    isbn VARCHAR(20) UNIQUE,
    category_id INT,
    total_copies INT,
    available_copies INT,
    published_year INT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

mysqli_query($conn, $create_books);
echo "✓ Tabela books u krijua<br>";

// Tabela 3: Përdoruesit e Bibliotekës
$create_members = "CREATE TABLE IF NOT EXISTS members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    membership_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
)";

mysqli_query($conn, $create_members);
echo "✓ Tabela members u krijua<br>";

// Tabela 4: Huazimet
$create_loans = "CREATE TABLE IF NOT EXISTS loans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT,
    book_id INT,
    loan_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date DATETIME,
    return_date DATETIME,
    status ENUM('active', 'returned', 'overdue') DEFAULT 'active'
)";

mysqli_query($conn, $create_loans);
echo "✓ Tabela loans u krijua<br>";

echo "<br>=======================================<br><br>";

// ============================================
// HAPI 3: FUNKSIONET PËR KATEGORIT
// ============================================

function add_category($conn, $name, $description) {
    $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";
    
    if (mysqli_query($conn, $sql)) {
        return "✅ Kategoria '$name' u shtua!";
    } else {
        return "❌ Gabim: " . mysqli_error($conn);
    }
}

function get_categories($conn) {
    $sql = "SELECT * FROM categories";
    return mysqli_query($conn, $sql);
}

// ============================================
// HAPI 4: FUNKSIONET PËR LIBRAT
// ============================================

function add_book($conn, $title, $author, $isbn, $category_id, $copies, $year, $desc) {
    $sql = "INSERT INTO books (title, author, isbn, category_id, total_copies, available_copies, published_year, description) 
            VALUES ('$title', '$author', '$isbn', $category_id, $copies, $copies, $year, '$desc')";
    
    if (mysqli_query($conn, $sql)) {
        return "✅ Libri '$title' u shtua!";
    } else {
        return "❌ Gabim: " . mysqli_error($conn);
    }
}

function get_all_books($conn) {
    $sql = "SELECT b.*, c.name as category_name 
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.id
            ORDER BY b.title ASC";
    return mysqli_query($conn, $sql);
}

function get_available_books($conn) {
    $sql = "SELECT b.*, c.name as category_name 
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.available_copies > 0
            ORDER BY b.title ASC";
    return mysqli_query($conn, $sql);
}

function search_books($conn, $search) {
    $sql = "SELECT b.*, c.name as category_name 
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.title LIKE '%$search%' OR b.author LIKE '%$search%'";
    return mysqli_query($conn, $sql);
}

// ============================================
// HAPI 5: FUNKSIONET PËR PËRDORUESIT
// ============================================

function add_member($conn, $name, $email, $phone, $address) {
    $sql = "INSERT INTO members (name, email, phone, address) 
            VALUES ('$name', '$email', '$phone', '$address')";
    
    if (mysqli_query($conn, $sql)) {
        $member_id = mysqli_insert_id($conn);
        return "✅ Anëtari '$name' u regjistrua! (ID: $member_id)";
    } else {
        return "❌ Gabim: " . mysqli_error($conn);
    }
}

function get_members($conn) {
    $sql = "SELECT * FROM members WHERE status = 'active' ORDER BY name";
    return mysqli_query($conn, $sql);
}

function get_member_info($conn, $member_id) {
    $sql = "SELECT * FROM members WHERE id = $member_id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

// ============================================
// HAPI 6: FUNKSIONET PËR HUAZIMET
// ============================================

function loan_book($conn, $member_id, $book_id, $days = 14) {
    // Kontrolloni nëse libri disponohet
    $check_sql = "SELECT available_copies FROM books WHERE id = $book_id";
    $result = mysqli_query($conn, $check_sql);
    $book = mysqli_fetch_assoc($result);
    
    if ($book['available_copies'] <= 0) {
        return "❌ Libri nuk disponohet!";
    }
    
    // Llogaritni datën e kthimit
    $due_date = date('Y-m-d H:i:s', strtotime("+$days days"));
    
    // Shtoni huazimin
    $sql = "INSERT INTO loans (member_id, book_id, due_date) 
            VALUES ($member_id, $book_id, '$due_date')";
    
    if (mysqli_query($conn, $sql)) {
        // Përditeso kopjat e disponueshme
        $update_sql = "UPDATE books SET available_copies = available_copies - 1 WHERE id = $book_id";
        mysqli_query($conn, $update_sql);
        
        return "✅ Libri u huazua! Duhet të kthehet deri: $due_date";
    } else {
        return "❌ Gabim: " . mysqli_error($conn);
    }
}

function return_book($conn, $loan_id) {
    // Merrni informacionin e huazimit
    $loan_sql = "SELECT * FROM loans WHERE id = $loan_id";
    $result = mysqli_query($conn, $loan_sql);
    $loan = mysqli_fetch_assoc($result);
    
    if (!$loan) {
        return "❌ Huazimi nuk u gjet!";
    }
    
    // Përditeso huazimin
    $update_sql = "UPDATE loans SET return_date = NOW(), status = 'returned' WHERE id = $loan_id";
    
    if (mysqli_query($conn, $update_sql)) {
        // Përditeso kopjat e disponueshme
        $book_update = "UPDATE books SET available_copies = available_copies + 1 WHERE id = " . $loan['book_id'];
        mysqli_query($conn, $book_update);
        
        return "✅ Libri u kthye me sukses!";
    } else {
        return "❌ Gabim: " . mysqli_error($conn);
    }
}

function get_member_loans($conn, $member_id) {
    $sql = "SELECT l.id, l.loan_date, l.due_date, l.return_date, l.status, b.title, b.author
            FROM loans l
            INNER JOIN books b ON l.book_id = b.id
            WHERE l.member_id = $member_id
            ORDER BY l.loan_date DESC";
    return mysqli_query($conn, $sql);
}

function get_active_loans($conn) {
    $sql = "SELECT l.id, l.loan_date, l.due_date, m.name as member_name, b.title as book_title, l.status
            FROM loans l
            INNER JOIN members m ON l.member_id = m.id
            INNER JOIN books b ON l.book_id = b.id
            WHERE l.status = 'active'
            ORDER BY l.due_date ASC";
    return mysqli_query($conn, $sql);
}

// ============================================
// HAPI 7: FUNKSIONET PËR STATISTIKAT
// ============================================

function get_statistics($conn) {
    $stats = array();
    
    // Numri i librave
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM books");
    $row = mysqli_fetch_assoc($result);
    $stats['total_books'] = $row['total'];
    
    // Numri i kopjave të disponueshme
    $result = mysqli_query($conn, "SELECT SUM(available_copies) as total FROM books");
    $row = mysqli_fetch_assoc($result);
    $stats['available_books'] = $row['total'] ?? 0;
    
    // Numri i anëtarëve
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM members");
    $row = mysqli_fetch_assoc($result);
    $stats['total_members'] = $row['total'];
    
    // Numri i huazimeve aktive
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM loans WHERE status = 'active'");
    $row = mysqli_fetch_assoc($result);
    $stats['active_loans'] = $row['total'];
    
    return $stats;
}

echo "✓ Të gjithë funksionet u përgatitur!<br><br>";

// ============================================
// HAPI 8: TESTIMI I SISTEMIT
// ============================================

echo "<h2>📚 SISTEM BIBLIOTEKE - TESTIMET</h2>";

// 1. Shtimi i kategorive
echo "<br><strong>1️⃣ Shtimi i kategorive:</strong><br>";
echo add_category($conn, "Letërsi Shqiptare", "Vepra të autorëve shqiptarë") . "<br>";
echo add_category($conn, "Letërsi Botërore", "Vepra të autorëve botërorë") . "<br>";
echo add_category($conn, "Shkencë", "Libra shkencorë") . "<br>";

// 2. Shtimi i librave
echo "<br><strong>2️⃣ Shtimi i librave:</strong><br>";
echo add_book($conn, "Përmet", "Ismail Kadare", "ISBN001", 1, 5, 1954, "Roman klasik shqiptar") . "<br>";
echo add_book($conn, "Jeta e Re", "Ismail Kadare", "ISBN002", 1, 3, 1960, "Koleksion përrallash") . "<br>";
echo add_book($conn, "1984", "George Orwell", "ISBN003", 2, 4, 1949, "Roman distopiik") . "<br>";
echo add_book($conn, "Principia", "Isaac Newton", "ISBN004", 3, 2, 1687, "Libri themelor i fizikës") . "<br>";

// 3. Regjistrimi i anëtarëve
echo "<br><strong>3️⃣ Regjistrimi i anëtarëve:</strong><br>";
echo add_member($conn, "Arjon Zharku", "arjon@email.com", "0692123456", "Tiranë") . "<br>";
echo add_member($conn, "Manjola Berisha", "manjola@email.com", "0693456789", "Durrës") . "<br>";
echo add_member($conn, "Sokol Çaka", "sokol@email.com", "0694567890", "Vlorë") . "<br>";

// 4. Huazimet
echo "<br><strong>4️⃣ Huazimi i librave:</strong><br>";
echo loan_book($conn, 1, 1, 14) . "<br>";
echo loan_book($conn, 2, 3, 14) . "<br>";
echo loan_book($conn, 3, 2, 10) . "<br>";

echo "<br><strong>5️⃣ SHFAQJA E LIBRAVE TË DISPONUESHËM:</strong><br>";
echo "<table border='1' cellpadding='10' style='margin: 10px 0;'>";
echo "<tr><th>📚 Libri</th><th>✍️ Autori</th><th>📂 Kategoria</th><th>📊 Disponueshëm</th></tr>";

$books = get_available_books($conn);
while ($book = mysqli_fetch_assoc($books)) {
    echo "<tr>";
    echo "<td>" . $book['title'] . "</td>";
    echo "<td>" . $book['author'] . "</td>";
    echo "<td>" . $book['category_name'] . "</td>";
    echo "<td><strong>" . $book['available_copies'] . "/" . $book['total_copies'] . "</strong></td>";
    echo "</tr>";
}
echo "</table>";

echo "<br><strong>6️⃣ HUAZIMET AKTIVE:</strong><br>";
echo "<table border='1' cellpadding='10' style='margin: 10px 0;'>";
echo "<tr><th>👤 Anëtari</th><th>📚 Libri</th><th>📅 Huazuar</th><th>🔔 Duhet Kthyer</th><th>Status</th></tr>";

$loans = get_active_loans($conn);
while ($loan = mysqli_fetch_assoc($loans)) {
    $due = strtotime($loan['due_date']);
    $now = strtotime(date('Y-m-d H:i:s'));
    $status = ($due < $now) ? "⚠️ VONESE" : "✓ Aktive";
    
    echo "<tr>";
    echo "<td>" . $loan['member_name'] . "</td>";
    echo "<td>" . $loan['book_title'] . "</td>";
    echo "<td>" . date('d.m.Y', strtotime($loan['loan_date'])) . "</td>";
    echo "<td>" . date('d.m.Y', strtotime($loan['due_date'])) . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<br><strong>7️⃣ STATISTIKA:</strong><br>";
$stats = get_statistics($conn);
echo "📚 Total Libra: <strong>" . $stats['total_books'] . "</strong><br>";
echo "📖 Kopje të Disponueshme: <strong>" . $stats['available_books'] . "</strong><br>";
echo "👥 Anëtarë: <strong>" . $stats['total_members'] . "</strong><br>";
echo "🔄 Huazime Aktive: <strong>" . $stats['active_loans'] . "</strong><br>";

// Kthimi i librave
echo "<br><strong>8️⃣ Kthimi i librave:</strong><br>";
echo return_book($conn, 1) . "<br>";
echo return_book($conn, 2) . "<br>";

// Përditesimi i statistikave pas kthimit
echo "<br><strong>9️⃣ STATISTIKA PËRPARA MBYLLJES:</strong><br>";
$stats = get_statistics($conn);
echo "📖 Kopje të Disponueshme (Para): <strong>" . $stats['available_books'] . "</strong><br>";

// Mbyllni lidhjen
mysqli_close($conn);

echo "<br><br>✅ <strong>Sistem Biblioteke u ndërtua me sukses!</strong>";
?>
