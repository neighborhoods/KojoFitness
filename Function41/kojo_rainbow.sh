alias kojo_rainbow='awk '"'"'{
  gsub("namespace_lock_v1", "\033[46m&\033[0m");
  gsub("namespace_lock_v2", "\033[44m&\033[0m");
  gsub("new_worker", "\033[1;36m&\033[0m");
  gsub("working", "\033[1;33m&\033[0m");
  gsub("complete_success", "\033[1;32m&\033[0m");
  print }'"'"'
