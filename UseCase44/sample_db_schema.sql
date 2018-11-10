CREATE TYPE sample_enum AS ENUM ('first value', 'second value');

CREATE TABLE sample_table (
  sample_column sample_enum
);
