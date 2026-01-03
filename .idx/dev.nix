{ pkgs, ... }: {
  # Let Nix handle a few common Nix files for you
  imports = [
    # Automatically install extensions recommended by your colleagues
    ./extensions.nix
    # Automatically configure previews for your ports
    ./previews.nix
  ];

  # The list of packages to make available in your workspace
  packages = [
    pkgs.php82
  ];

  # Sets environment variables in the workspace
  env = {};
  idx = {
    # Search for the extensions you want on https://open-vsx.org/ and use "publisher.id"
    extensions = [
      # "vscodevim.vim"
      "bmewburn.vscode-intelephense-client"
    ];

    # Enable previews
    previews = {
      enable = true;
      previews = [{
        # Example: run "npm run dev" with PORT set to IDX's defined port for previews,
        # and show it in IDX's web preview panel
        command = [ "php" "-S" "0.0.0.0:$PORT" ];
        manager = "web";
      }];
    };

    # Workspace lifecycle hooks
    workspace = {
      # Runs when a workspace is first created
      onCreate = {
        # Example: install JS dependencies from NPM
        # npm-install = "npm install";
      };
      # Runs when the workspace is (re)started
      onStart = {
        # Example: start a background task to watch and re-build backend code
        # watch-backend = "npm run watch-backend";
      };
    };
  };
}
