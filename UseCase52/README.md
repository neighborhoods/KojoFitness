# Defines the fitness of Kōjō for
- Basic functionality in the 5.x branch

## Setup UseCase52

### Running Kōjō
Setting up the containers using docker-compose:

```bash
# Start the containers
# Create a database
# Prepare the Kōjō runtime and register jobs
make build

# Run Kōjō
make start

# After you see "working" events stop and a few "complete_success" then all messages have been deleted. Press ctrl+c

# Delete the Kōjō Tables and clear redis
make clean
```
