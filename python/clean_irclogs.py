import os
import shutil


def getFiles(path):
  for f in os.listdir(path):
    pathname = os.path.join(path, f)
    if (os.path.isdir(pathname)):
      getFiles(pathname)
    else:
      cleanFile(pathname)
  if not sum([len(files) for r, d, files in os.walk(path)]):
    os.rmdir(path)


def cleanFile(file):
  temp = '/tmp/clean_python.tmp'
  f = open(file, 'rb')
  t = open(temp, 'w');
  reject_count = 0;
  for line in f:
      if (    not 'has left' in line
          and not 'has joined' in line
          and not 'have joined' in line
          and not ' Mode:' in line
          and not ' Created at: ' in line
          and not ' is now known as ' in line
          and not ' Disconnected' in line
          and not ' set the topic at: ' in line
          and not ' has set topic: ' in line
          and not ' Topic: ' in line
          and not ' ***: Buffer Playback...' in line
          and not ' ***: Playback Complete.' in line
          and not ' You have left the channel' in line
          and not ' has changed mode:' in line
          and not ' irccat: ' in line
          and not ' reminderbot: ' in line
          and not ' github: ' in line
          and not ' devbot: ' in line
          and not ' Jenkins: ' in line
          and not ' pushbot: ' in line
          ):
        t.write(line);
      else:
        reject_count = reject_count + 1
  t.close()
  f.close()
  if reject_count:
    if os.path.getsize(temp):
      shutil.copyfile(temp, file)
    else:
      os.remove(file)

def main ():
  path = '/Users/cclark/Documents/LimeChat Transcripts'
  #path = 'irclogs2'
  getFiles(path)

if __name__== "__main__":
  main()
