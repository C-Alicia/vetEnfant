# wait-for-it.sh: un script d'attente pour vérifier qu'un service est prêt à être utilisé.
# Usage : ./wait-for-it.sh <host>:<port> -- <command>
# Exemple : ./wait-for-it.sh database:3306 -- symfony serve --port=8000

host="$1"
shift
port="$1"
shift
cmd="$@"

# Attente que le service soit accessible sur le port
until nc -z -v -w30 $host $port; do
  echo "Waiting for $host:$port..."
  sleep 1
done

echo "$host:$port is up. Executing command."
exec $cmd
