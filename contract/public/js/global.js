function delete_confirm(){
    if (confirm("Вы уверены, что хотите удалить запись?")) {
        return true;
    } else {
        return false;
    }
}