# xxd Command Guide: Creating and Manipulating Hex Dumps

## Introduction

`xxd` is a Unix utility that creates a hex dump of a given file or standard input. It can also convert a hex dump back to its original binary form. This makes it invaluable for:

- Viewing/editing binary files
- Debugging data formats
- Creating binary files from hex specifications
- Reverse engineering file formats
- Learning about file structures

## Basic Syntax

```bash
xxd [options] [infile [outfile]]
```

Common options:
- `-l len` : stop after <len> octets
- `-c cols` : format <cols> octets per line
- `-p` : output in postscript continuous hexdump style
- `-r` : reverse operation: convert hex dump into binary
- `-i` : output in C include file style
- `-s [+][-]seek` : start at <seek> bytes abs./rel. position

## Examples

### 1. Creating a Basic Hexdump

```bash
# Create hex dump of a file
$ echo "Hello, World!" > test.txt
$ xxd test.txt
00000000: 4865 6c6c 6f2c 2057 6f72 6c64 210a  Hello, World!.
```

### 2. Controlling Output Format

```bash
# Display 8 bytes per line
$ xxd -c 8 test.txt
00000000: 4865 6c6c 6f2c 2057  Hello, W
00000008: 6f72 6c64 210a       orld!.

# Plain hex output without line numbers and ASCII
$ xxd -p test.txt
48656c6c6f2c20576f726c64210a
```

### 3. Creating C Arrays

```bash
# Generate C include file
$ xxd -i test.txt
unsigned char test_txt[] = {
0x48, 0x65, 0x6c, 0x6c, 0x6f, 0x2c, 0x20, 0x57, 0x6f, 0x72, 0x6c, 0x64,
0x21, 0x0a
};
unsigned int test_txt_len = 14;
```

### 4. Reverse Operation

```bash
# Convert hex dump back to binary
$ xxd -p test.txt > hex.txt
$ xxd -r -p hex.txt > recovered.txt
$ cat recovered.txt
Hello, World!
```

## Practical Use Cases

1. **Binary File Analysis**
```bash
# Examine file headers
$ xxd -l 32 image.jpg
```

2. **Data Recovery**
```bash
# Extract specific bytes
$ xxd -s 0x1000 -l 512 disk.img
```

3. **File Patching**
```bash
# Create hex dump
$ xxd file.bin > file.hex
# Edit file.hex
# Convert back
$ xxd -r file.hex > file.patched
```

4. **Network Packet Analysis**
```bash
# Examine captured packets
$ tcpdump -X | xxd
```

## Tips and Tricks

1. **Skipping Portions**
```bash
# Skip first 1KB
$ xxd -s 1024 file.bin
```

2. **Relative Seeking**
```bash
# Seek backwards from current position
$ xxd -s -512 file.bin
```

3. **Custom Width Display**
```bash
# Show 16 bytes per line with group size 1
$ xxd -c 16 -g 1 file.bin
```

## Common Options Explained

| Option | Description | Example |
|--------|-------------|---------|
| `-a` | Toggle autoskip: A single '*' replaces nul-lines | `xxd -a file.bin` |
| `-b` | Binary digit dump (incompatible with -ps,-i,-r) | `xxd -b file.bin` |
| `-c N` | Format N bytes per line | `xxd -c 8 file.bin` |
| `-E` | Show characters in EBCDIC instead of ASCII | `xxd -E file.bin` |
| `-g N` | Number of bytes per group | `xxd -g 1 file.bin` |
| `-i` | Output in C include file style | `xxd -i file.bin` |
| `-l N` | Stop after N bytes | `xxd -l 256 file.bin` |
| `-p` | Plain hexdump style | `xxd -p file.bin` |
| `-r` | Reverse operation | `xxd -r file.hex` |
| `-s N` | Start at offset N | `xxd -s 512 file.bin` |
| `-u` | Use uppercase hex letters | `xxd -u file.bin` |

Remember to check `man xxd` for the complete list of options and detailed explanations.

