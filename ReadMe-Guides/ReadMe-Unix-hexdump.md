# Unix Hexdump Guide

## Overview
Hexdump is a Unix utility that displays file contents in hexadecimal, decimal, and ASCII formats. It's invaluable for examining binary files, troubleshooting data encoding issues, and analyzing file formats.

## Basic Usage
The basic syntax is:
```bash
hexdump [options] file
```

Common options:
- `-C`: Canonical hex+ASCII display
- `-v`: Display all lines (don't collapse identical lines)
- `-n length`: Only dump length bytes
- `-s offset`: Skip offset bytes from start

## Common Formats and Options

### 1. Canonical Format (-C)
```bash
$ hexdump -C example.txt
00000000  48 65 6c 6c 6f 20 57 6f  72 6c 64 21 0a        |Hello World!.|
0000000d
```

### 2. Hex Only (-x)
```bash
$ hexdump -x example.txt
0000000    6548  6c6c  206f  6f57  6c72  2164  000a
000000d
```

### 3. Decimal Format (-d)
```bash
$ hexdump -d example.txt
0000000    25928  27756  08288  28263  27748  25924  00010
000000d
```

## Real-World Use Cases

### 1. Binary File Inspection
When debugging compiled programs:
```bash
$ hexdump -C program.exe | head -n 10
00000000  7f 45 4c 46 02 01 01 00  00 00 00 00 00 00 00 00  |.ELF............|
00000010  02 00 3e 00 01 00 00 00  30 04 40 00 00 00 00 00  |..>.....0.@.....|
```

### 2. File Format Analysis
Checking PDF file header:
```bash
$ hexdump -C document.pdf | head -n 1
00000000  25 50 44 46 2d 31 2e 34  0a 25 e2 e3 cf d3     |%PDF-1.4.%....|
```

### 3. Network Packet Analysis
Examining captured network packets:
```bash
$ hexdump -C packet.cap | head -n 4
00000000  d4 c3 b2 a1 02 00 04 00  00 00 00 00 00 00 00 00  |................|
00000010  00 00 04 00 01 00 00 00  7c 8b 61 64 82 52 0b 00  |........|.ad.R..|
```

### 4. Character Encoding Issues
Identifying encoding problems:
```bash
# Check UTF-8 BOM
$ hexdump -C utf8file.txt | head -n 1
00000000  ef bb bf 48 65 6c 6c 6f  20 57 6f 72 6c 64      |...Hello World|
```

## Best Practices

1. **Use -C for General Inspection**
- The canonical format (-C) provides the most readable output
- Shows both hex and ASCII representation
- Useful for quick file analysis

2. **Skip Headers with -s**
```bash
# Skip first 512 bytes
hexdump -C -s 512 largefile.bin
```

3. **Limit Output with -n**
```bash
# Only show first 32 bytes
hexdump -C -n 32 largefile.bin
```

4. **Compare Files**
```bash
# Compare two files
cmp <(hexdump file1) <(hexdump file2)
```

5. **Search for Patterns**
```bash
# Find specific byte sequence
hexdump -C file | grep "ff d8 ff"
```

## Tips for File Analysis

1. **Common File Signatures**
- JPEG: `FF D8 FF`
- PNG: `89 50 4E 47`
- PDF: `25 50 44 46`
- ZIP: `50 4B 03 04`

2. **Examine File Boundaries**
```bash
# Check file end
hexdump -C -s -32 file | tail -n 2
```

3. **Handle Large Files**
```bash
# Display specific portion
hexdump -C -s $((16#1000)) -n 256 largefile.bin
```

## Troubleshooting Guide

### 1. Common File Corruption Scenarios
```bash
# Check for NUL bytes that may indicate corruption
$ hexdump -C file.txt | grep "00 00 00 00"

# Look for unexpected byte patterns
$ hexdump -C file.txt | grep -v "|.*|" | grep "ff ff ff ff"

# Verify file endings
$ hexdump -C -s -32 file.txt | tail -n 2
```

### 2. Text Encoding Issues Detection
```bash
# Check for UTF-8 BOM
$ hexdump -C file.txt | head -n 1
00000000  ef bb bf 68 65 6c 6c 6f  |...hello|

# Look for UTF-16 indicators
$ hexdump -C file.txt | head -n 1
00000000  ff fe 68 00 65 00 6c 00  |..h.e.l.|

# Find non-ASCII characters
$ hexdump -C file.txt | grep -v "[[:ascii:]]"
```

### 3. Integration with Other Tools
```bash 
# Use with grep:
$ hexdump -C file.bin | grep "4d 5a"  # Find DOS executables

# Use with awk:
$ hexdump -c file.txt | awk '/\\0/ {print NR}'  # Find null bytes

# Use with sed:
$ hexdump -C file.bin | sed -n '/89 50 4e 47/,+5p'  # Show PNG header
```

### 4. Memory Analysis Examples
```bash
# Analyze core dumps
$ hexdump -C core.dump | grep -A 5 "deadbeef"

# Find memory patterns
$ hexdump -C memory.bin | grep "aa aa aa aa"

# Stack analysis
$ hexdump -C -s $((16#1000)) -n 256 stack.dump
```

### 5. Database File Analysis 
```bash
# SQLite database header check
$ hexdump -C -n 16 database.db
00000000  53 51 4c 69 74 65 20 66  6f 72 6d 61 74 20 33 00  |SQLite format 3.|

# Find table definitions
$ hexdump -C database.db | grep -A 10 "CREATE TABLE"

# Index validation
$ hexdump -C database.db | grep -A 5 "idx1"
```

### 6. Network Packet Analysis
```bash
# TCP header inspection
$ hexdump -C packet.cap | grep -A 2 "02 00 00 00"

# DNS packet analysis
$ hexdump -C dns.cap | grep -A 5 "00 35"  # Port 53

# HTTP request parsing
$ hexdump -C http.cap | grep -B 2 "47 45 54"  # GET
```

### 7. Performance Considerations
- Use `-s offset` and `-n length` to limit output for large files
- Pipe through grep/awk for faster pattern matching
- Consider using `xxd -p` for faster hex-only output
- Use `-v` sparingly as it can generate large outputs
- Combine with `head`/`tail` to limit output size

```bash
# Example of efficient large file analysis
$ hexdump -s $((16#1000000)) -n 4096 -C largefile.bin | grep pattern

# Process in chunks
$ for i in {0..10}; do
    offset=$((i*1024))
    hexdump -s $offset -n 1024 -C file.bin | grep pattern
done
```

Remember to:
- Plan search strategy before analysis
- Test patterns on small samples first 
- Use appropriate buffering for large files
- Document unusual patterns found
- Keep session logs for reference
