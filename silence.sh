#!/usr/bin/perl

$seconds = $ARGV[0];
$file = $ARGV[1];
if ((!$seconds) || ($file eq "")) {
        die "Usage: silence seconds newfilename.wav\n";
}

open(OUT, ">/tmp/$$.dat");
print OUT "; SampleRate 8000\n";
$samples = $seconds * 8000;
for ($i = 0; ($i < $samples); $i++) {
        print OUT $i / 8000, "\t0\n";
}
close(OUT);

system("sox /tmp/$$.dat -b 16 -r 44100 -c 2 -e signed-integer  $file");
unlink("/tmp/$$.dat");
