# KōjōFitness
Fitness functions for [Kōjō](https://github.com/neighborhoods/kojo).

These are examples to prove and provide insight into specific aspects and attributes of Kojo.

Each `UseCaseXY` directory contains a README.md detailing its specifics.
`X` refers to the major version of Kojo. `Y` is an increment for the function, but is not tied to the minor/patch version of kojo.

- UseCase40: Read messages from SQS and delete them
- UseCase41: Two side-by-side servers that should both be able to do work at the same time
- UseCase42: Throw a TypeError deep in the worker and check that Kōjō logs useful info to indicate that fact
- UseCase43 : DLC pattern with graceful bow out
- UseCase44: Setup Kōjō database on an environment with non-primitive column types
- UseCase45: A single worker that sometimes requests a retry instead of doing its job
- UseCase46: Logging a mix of complex worker status messages and uncaught exceptions
