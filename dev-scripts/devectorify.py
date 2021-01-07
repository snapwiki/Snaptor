import fileinput
import glob

with open("skin.json") as skin_manifest:
    content = skin_manifest.read()
    content = content.replace("Vector", "Snaptor")
    skin_manifest.write(s=content)
#ile = open("skin.json").read()
#file = file.replace("Vector", "Snaptor")
#file.close()