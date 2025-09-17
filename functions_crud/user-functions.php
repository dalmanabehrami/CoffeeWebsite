<?php
// /functions_crud/user-functions.php

function createUser($conn, $name, $email, $password, $role = 'user') {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed, $role);
    if(mysqli_stmt_execute($stmt)) {
        return ['success' => true, 'id' => mysqli_insert_id($conn)];
    }
    return ['success' => false, 'error' => mysqli_error($conn)];
}

function getAllUsers($conn) {
    $result = mysqli_query($conn, "SELECT id, name, email, role FROM users ORDER BY id DESC");
    $users = [];
    while($row = mysqli_fetch_assoc($result)) $users[] = $row;
    return $users;
}

function getUserById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, email, role FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

function updateUser($conn, $id, $name, $email, $role) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET name=?, email=?, role=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $role, $id);
    return mysqli_stmt_execute($stmt);
}

function deleteUser($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

?>
