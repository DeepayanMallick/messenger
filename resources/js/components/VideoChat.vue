<template>
  <div class="fullwrap">
    <div class="Sidebar">
      <div class="SearchWrap">
        <div class="SearchUser">
          <form action="">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search" name="userprofile" class="formcontrol">
          </form>
        </div>
        <div class="moreInfo">
          <svg xmlns="http://www.w3.org/2000/svg" width="20px" aria-hidden="true" viewBox="0 0 14 14" role="img">
            <path fill-rule="evenodd"
              d="M3.52 6.75c0 .966-.789 1.75-1.76 1.75A1.755 1.755 0 010 6.75C0 5.784.788 5 1.76 5c.971 0 1.76.784 1.76 1.75m10.48 0c0 .966-.788 1.75-1.76 1.75a1.755 1.755 0 01-1.759-1.75c0-.966.788-1.75 1.759-1.75.972 0 1.76.784 1.76 1.75m-5.24 0c0 .966-.788 1.75-1.76 1.75a1.755 1.755 0 01-1.76-1.75C5.24 5.784 6.03 5 7 5c.972 0 1.76.784 1.76 1.75">
            </path>
          </svg>
        </div>
      </div>
      <div class="ProfileWrap">
        <button type="button" class="mr-2" style="" v-for="user in allusers" :key="user.id"
          @click="placeUserClick(user.id, user.name)">
          <i class="fa fa-user"></i> {{ user.name }}
          <!-- <span class="badge badge-success">
                    {{
                        getUserOnlineStatus(user.id)
                    }}
                    </span> -->
          <span class="badge" v-html="getUserOnlineStatus(user.id)"></span>
        </button>
      </div>
    </div>

    <div class="ContentArea">

      <div class="ChatHeader">
        <span v-html="selectedUserName"></span>
        <button v-if="selectedUserID" id="callButton" @click="placeVideoCall(selectedUserID, selectedUserName);"><i class="fa fa-video"></i>
        </button>
      </div>

      <div v-html="messageOutput" id="messageOutput">
      </div>
      
      <div class="ChatFooter" v-if="selectedUserID">
        <form id="chatForm" @submit.prevent="sendMessage">
          <div class="form-group mb-3">
            <input v-model="inputContent" class="WriteMsg" ref="inputField" @focus="handleInputFocus"
              @blur="handleInputBlur" placeholder="Write a message ..." />
          </div>

          <div class="chatFormFooter">
            <div class="chatFormFooterLeft">
              <input type="file" id="fileInput" ref="fileInput" />

              <button id="uploadButton" class="btn btn-primary" @click="uploadFile">Upload</button>
            </div>

            <div class="chatFormFooterRight">

              <div ref="emojiPicker">

                <div class="emoji_picker" ref="emojiPicker" v-if="isEmojiPickerOpen">
                  <div class="picker_container">
                    <div class="category" v-for="category in categories" :key="`category_${category}`">
                      <span>{{ category }}</span>
                      <div class="emojis_container">
                        <a @click.prevent="handleEmojiClick(emoji)" v-for="(emoji, index) in category_emojis(category)"
                          :key="`emoji_${index}`">
                          {{ emoji }}
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="bottom_arrow" v-if="show_arrow"></div>
                </div>

                <a @click.prevent="toggleEmojiPicker"><i class="fa fa-smile"></i></a>

              </div>

              <button type="submit"><i class="fa fa-paper-plane"></i></button>
            </div>

          </div>

        </form>

        <div id="message"></div>


      </div>

      <div id="video-row">
        <div class="video-container" v-if="callPlaced">
          <video ref="userVideo" muted playsinline autoplay class="cursor-pointer"
            :class="isFocusMyself === true ? 'user-video' : 'partner-video'" @click="toggleCameraArea" />
          <video ref="partnerVideo" playsinline autoplay class="cursor-pointer"
            :class="isFocusMyself === true ? 'partner-video' : 'user-video'" @click="toggleCameraArea"
            v-if="videoCallParams.callAccepted" />
          <div class="partner-video" v-else>
            <div v-if="callPartner" class="column items-center q-pt-xl">
              <div class="col q-gutter-y-md text-center">
                <p class="q-pt-md">
                  <strong>{{ callPartner }}</strong>
                </p>
                <p>calling...</p>
              </div>
            </div>
          </div>
          <div class="action-btns">
            <button type="button" class="btn btn-info" @click="toggleMuteAudio">
              {{ mutedAudio ? "Unmute" : "Mute" }}
            </button>
            <button type="button" class="btn btn-primary mx-4" @click="toggleMuteVideo">
              {{ mutedVideo ? "ShowVideo" : "HideVideo" }}
            </button>
            <button type="button" class="btn btn-danger" @click="endCall">
              EndCall
            </button>
          </div>
        </div>
      </div>

      <!-- Incoming Call  -->
      <div class="IncomingCallBox" v-if="incomingCallDialog">
        <div class="col">
          <p>
            Incoming Call From <strong>{{ callerDetails.name }}</strong>
          </p>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="declineCall">
              Decline
            </button>
            <button type="button" class="btn btn-success ml-5" @click="acceptCall">
              Accept
            </button>
          </div>
        </div>
      </div>
      <!-- End of Incoming Call  -->

    </div>

    <div id="sidebar-layout" class="sidebar-layout">
      <div class="ProfileWrap">
        <div class="ProfileImage">
          <i class="fa fa-user"></i>
          <span class="online"></span>
        </div>
        <div class="ProfileContent">
          <h3>Anika Jonesone</h3>
          <h5>SaveOnDev</h5>
          <p>9:33 BD (13 h behind)</p>
          <p><i class="fa fa-briefcase"></i> Backend Developer</p>
        </div>
      </div>

      <div class="Sidebarmenu">
        <ul>
          <li>
            <a href="#">
              <i class="fa fa-search"></i>
              Search Messages
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-user"></i>
              People
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-link"></i>
              Files & Link
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fa fa-address-book"></i>
              Personal notepad
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
  import Peer from "simple-peer";
  import { getPermissions } from "../helpers";
  import data from './emojis-data.json';
  export default {
    props: [
      "allusers",
      "ccccc",
      "turn_url",
      "authuserid",
      "turn_username",
      "turn_credential",
      "fromusername"
    ],
    data() {
      return {
        messageElement: '',
        messageOutput: '',
        message: '',
        fromUserID: null, // Set your fromUserID
        authUserId: this.authuserid, // Set your fromUserID
        toUserID: null, // Set your toUserID
        isFocusMyself: true,
        callPlaced: false,
        callPartner: null,
        selectedUserID: null,
        selectedUserName: "",
        fromUserName: this.fromusername,
        mutedAudio: false,
        mutedVideo: false,
        videoCallParams: {
          users: [],
          stream: null,
          receivingCall: false,
          caller: null,
          callerSignal: null,
          callAccepted: false,
          channel: null,
          peer1: null,
          peer2: null,
        },
        isEmojiPickerOpen: false,
        inputContent: '',
        show_arrow: true,
        data: data,
        inputHasFocus: false,
      };
    },

    mounted() {
      this.initializeChannel(); // this initializes laravel echo
      this.initializeCallListeners(); // subscribes to video presence channel and listens to video events
      this.privateMsgEvent();
    },
    computed: {
      incomingCallDialog() {
        if (
          this.videoCallParams.receivingCall &&
          this.videoCallParams.caller !== this.authuserid
        ) {
          return true;
        }
        return false;
      },

      categories() {
        return Object.keys(data);
      },

      category_emojis: () => (category) => {
        return Object.values(data[category]);
      },

      callerDetails() {
        if (
          this.videoCallParams.caller &&
          this.videoCallParams.caller !== this.authuserid
        ) {
          const incomingCaller = this.allusers.filter(
            (user) => user.id === this.videoCallParams.caller
          );

          return {
            id: this.videoCallParams.caller,
            name: `${incomingCaller[0].name}`,
          };
        }
        return null;
      },
    },
    methods: {
      initializeChannel() {
        this.videoCallParams.channel = window.Echo.join("presence-video-channel");
      },

      toggleEmojiPicker() {
        this.inputHasFocus = true;
        this.isEmojiPickerOpen = !this.isEmojiPickerOpen;
        if (this.isEmojiPickerOpen) {
          this.setupClickOutsideListener();
        } else {
          this.removeClickOutsideListener();
        }
      },
      handleEmojiClick(emoji) {
        if (this.inputHasFocus) {
          const inputField = this.$refs.inputField;
          const startPos = inputField.selectionStart;
          const endPos = inputField.selectionEnd;
          this.inputContent = this.inputContent.substring(0, startPos) + emoji + this.inputContent.substring(endPos);
          const newCursorPosition = startPos + emoji.length;
          this.$nextTick(() => {
            inputField.setSelectionRange(newCursorPosition, newCursorPosition);
            inputField.focus();
          });
        } else {
          this.inputContent += emoji;
        }
      },
      handleInputFocus() {
        this.inputHasFocus = true;
      },
      handleInputBlur() {
        this.inputHasFocus = false;
      },
      setupClickOutsideListener() {
        console.log('Click Inside');
        document.addEventListener('click', this.clickOutsideHandler);
      },
      removeClickOutsideListener() {
        console.log('Click Outside');
        document.removeEventListener('click', this.clickOutsideHandler);
      },
      clickOutsideHandler(event) {
        const emojiPicker = this.$refs.emojiPicker;

        console.log(emojiPicker);

        if (emojiPicker && !emojiPicker.contains(event.target)) {
          this.isEmojiPickerOpen = false;
          this.removeClickOutsideListener();
        } else {
          console.log('Not working ...');
        }
      },

      privateMsgEvent() {
        window.Echo.channel("private-laravelChat").listen(".chatting", (res) => {
          if (res !== undefined && res != null) {
            let html = "";
            res.messageHistory.forEach((itm) => {
              html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + itm.from_user_details + ' <span>' + itm.message_time + '</span></h3><p>' + itm.message + '</p></div></div>';
            });
            this.messageOutput = html;
          }
        });
      },
      getMediaPermission() {
        return getPermissions()
          .then((stream) => {
            this.videoCallParams.stream = stream;
            if (this.$refs.userVideo) {
              this.$refs.userVideo.srcObject = stream;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      },

      initializeCallListeners() {
        this.videoCallParams.channel.here((users) => {
          this.videoCallParams.users = users;
        });

        this.videoCallParams.channel.joining((user) => {
          // check user availability
          const joiningUserIndex = this.videoCallParams.users.findIndex(
            (data) => data.id === user.id
          );
          if (joiningUserIndex < 0) {
            this.videoCallParams.users.push(user);
          }
        });

        this.videoCallParams.channel.leaving((user) => {
          const leavingUserIndex = this.videoCallParams.users.findIndex(
            (data) => data.id === user.id
          );
          this.videoCallParams.users.splice(leavingUserIndex, 1);
        });
        // listen to incomming call
        this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
          if (data.type === "incomingCall") {
            // add a new line to the sdp to take care of error
            const updatedSignal = {
              ...data.signalData,
              sdp: `${data.signalData.sdp}\n`,
            };

            this.videoCallParams.receivingCall = true;
            this.videoCallParams.caller = data.from;
            this.videoCallParams.callerSignal = updatedSignal;
          }
        });
      },
      placeUserClick(id, name) {
        this.selectedUserID = id;
        this.selectedUserName = name;
        axios.get("/msg-history/" + this.authUserId + "/" + id, {
        })
          .then((res) => {
            if (res.data.data !== undefined && res.data.data != null) {
              let html = "";
              res.data.data.forEach((itm) => {
                html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + itm.from_user_details + ' <span>' + itm.message_time + '</span></h3><p>' + itm.message + '</p></div></div>';
              });
              this.messageOutput = html;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      },
      async placeVideoCall(id, name) {
        this.callPlaced = true;
        this.callPartner = name;
        await this.getMediaPermission();
        this.videoCallParams.peer1 = new Peer({
          initiator: true,
          trickle: false,
          stream: this.videoCallParams.stream,
          config: {
            iceServers: [
              {
                urls: this.turn_url,
                username: this.turn_username,
                credential: this.turn_credential,
              },
            ],
          },
        });

        this.videoCallParams.peer1.on("signal", (data) => {
          // send user call signal
          axios
            .post("/video/call-user", {
              user_to_call: id,
              signal_data: data,
              from: this.authuserid,
            })
            .then(() => { })
            .catch((error) => {
              console.log(error);
            });
        });

        this.videoCallParams.peer1.on("stream", (stream) => {
          console.log("call streaming");
          if (this.$refs.partnerVideo) {
            this.$refs.partnerVideo.srcObject = stream;
          }
        });

        this.videoCallParams.peer1.on("connect", () => {
          console.log("peer connected");
        });

        this.videoCallParams.peer1.on("error", (err) => {
          console.log(err);
        });

        this.videoCallParams.peer1.on("close", () => {
          console.log("call closed caller");
        });

        this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
          if (data.type === "callAccepted") {
            if (data.signal.renegotiate) {
              console.log("renegotating");
            }
            if (data.signal.sdp) {
              this.videoCallParams.callAccepted = true;
              const updatedSignal = {
                ...data.signal,
                sdp: `${data.signal.sdp}\n`,
              };
              this.videoCallParams.peer1.signal(updatedSignal);
            }
          }
        });
      },
      listenForNewMessages() {
        window.Echo.channel('private-laravelChat').listen('.chatting', (res) => {
          let userName = 'anonymous';
          if (res.userName != null && res.userName != '') {
            userName = res.userName;
          }
          const message = {
            id: Date.now(), // Use a unique identifier for the key
            username: userName,
            message: window.emoji.replace_colons(res.message),
          };
          this.messages.push(message);
        });
      },

      async acceptCall() {
        this.callPlaced = true;
        this.videoCallParams.callAccepted = true;
        await this.getMediaPermission();
        this.videoCallParams.peer2 = new Peer({
          initiator: false,
          trickle: false,
          stream: this.videoCallParams.stream,
          config: {
            iceServers: [
              {
                urls: this.turn_url,
                username: this.turn_username,
                credential: this.turn_credential,
              },
            ],
          },
        });
        this.videoCallParams.receivingCall = false;
        this.videoCallParams.peer2.on("signal", (data) => {
          axios
            .post("/video/accept-call", {
              signal: data,
              to: this.videoCallParams.caller,
            })
            .then(() => { })
            .catch((error) => {
              console.log(error);
            });
        });

        this.videoCallParams.peer2.on("stream", (stream) => {
          this.videoCallParams.callAccepted = true;
          this.$refs.partnerVideo.srcObject = stream;
        });

        this.videoCallParams.peer2.on("connect", () => {
          console.log("peer connected");
          this.videoCallParams.callAccepted = true;
        });

        this.videoCallParams.peer2.on("error", (err) => {
          console.log(err);
        });

        this.videoCallParams.peer2.on("close", () => {
          console.log("call closed accepter");
        });

        this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);
      },
      toggleCameraArea() {
        if (this.videoCallParams.callAccepted) {
          this.isFocusMyself = !this.isFocusMyself;
        }
      },
      getUserOnlineStatus(id) {
        const onlineUserIndex = this.videoCallParams.users.findIndex(
          (data) => data.id === id
        );
        if (onlineUserIndex < 0) {
          //return "Offline";
          return "<span style='color: gray;'>Offline</span>";
        }
        //return "Online";
        return "<span style='color: green;'>Online</span>";
      },
      declineCall() {
        this.videoCallParams.receivingCall = false;
      },

      toggleMuteAudio() {
        if (this.mutedAudio) {
          this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = true;
          this.mutedAudio = false;
        } else {
          this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = false;
          this.mutedAudio = true;
        }
      },

      toggleMuteVideo() {
        if (this.mutedVideo) {
          this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = true;
          this.mutedVideo = false;
        } else {
          this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = false;
          this.mutedVideo = true;
        }
      },
      dateFormat(date) {
        //get hours, minutes, and seconds
        let hours = date.getHours();
        let minutes = date.getMinutes();
        let seconds = date.getSeconds();

        // convert hours to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12;

        // add leading zeros to minutes and seconds
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        // add am/pm suffix
        const amPm = hours >= 12 ? 'PM' : 'AM';

        // combine all parts into a time string
        const timeString = hours + ':' + minutes + ':' + seconds + ' ' + amPm;

        return timeString;
      },
      stopStreamedVideo(videoElem) {
        const stream = videoElem.srcObject;
        const tracks = stream.getTracks();
        tracks.forEach((track) => {
          track.stop();
        });
        videoElem.srcObject = null;
      },
      sendMessage() {
        if (this.inputContent !== '' && this.selectedUserID != '') {
          axios
            .post('/send-message', {
              message: this.inputContent,
              fromUserID: this.authuserid,
              toUserID: this.selectedUserID,
            })
            .then((res) => {
              console.log("res", res);
              if (res.config.data !== undefined && res.config.data != null) {
                let msgs = JSON.parse(res.config.data);
                let html = "";
                html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + this.fromUserName + ' <span>' + this.dateFormat(new Date()) + '</span></h3><p>' + msgs.message + '</p></div></div>';
                this.messageOutput += html;
              }
            })
            .catch((error) => {
              console.error(error);
            });

          this.inputContent = '';
        }
      },
      uploadFile() {
        const file = this.$refs.fileInput.files[0];
        if (file) {
          const formData = new FormData();
          formData.append('file', file);

          const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

          axios
            .post('/upload', formData, {
              headers: {
                'X-CSRF-TOKEN': csrfToken,
              },
            })
            .then((response) => {
              axios
                .post('/send-message', {
                  message: `<a target="_blank" href="${response.data.url}"><img width="100" src="/storage/uploads/free-thumb.png"></a><br><span>${response.data.message}</span>`,
                  fromUserID: this.authuserid,
                  toUserID: this.selectedUserID,
                })
                .then((res) => {
                  console.log("res", res);
                  if (res.config.data !== undefined && res.config.data != null) {
                    let msgs = JSON.parse(res.config.data);
                    let html = "";
                    html += '<div class="MsgWrap"><div class="Profile"><i class="fa fa-user"></i><span class="online"></span></div><div class="TextMsg"><h3>' + this.fromUserName + ' <span>' + this.dateFormat(new Date()) + '</span></h3><p>' + msgs.message + '</p></div></div>';
                    this.messageOutput += html;
                  }
                  // Listen for new chat messages using Echo or your preferred method
                })
                .catch((error) => {
                  console.error(error);
                });

              this.message = '';
            })
            .catch((error) => {
              this.message = 'Error uploading file.';
              console.error(error);
            });
        } else {
          this.message = 'Please select a file.';
        }
      },
      endCall() {
        // if video or audio is muted, enable it so that the stopStreamedVideo method will work
        if (!this.mutedVideo) this.toggleMuteVideo();
        if (!this.mutedAudio) this.toggleMuteAudio();
        this.stopStreamedVideo(this.$refs.userVideo);
        if (this.authuserid === this.videoCallParams.caller) {
          this.videoCallParams.peer1.destroy();
        } else {
          this.videoCallParams.peer2.destroy();
        }
        this.videoCallParams.channel.pusher.channels.channels[
          "presence-presence-video-channel"
        ].disconnect();

        setTimeout(() => {
          this.callPlaced = false;
        }, 3000);
      },
    },
  };
</script>

<style scoped>
  #video-row {
    width: 700px;
    max-width: 90vw;
    position: absolute;
    top: 0px;
    right: 0px
      /* top: 50%;    
    left: 50%;
    transform: translate(-50%, -50%); */
  }

  #incoming-call-card {
    border: 1px solid #0acf83;
  }

  .video-container {
    width: 700px;
    height: 500px;
    max-width: 90vw;
    max-height: 50vh;
    margin: 0 auto;
    border: 1px solid #0acf83;
    position: relative;
    box-shadow: 1px 1px 11px #9e9e9e;
    background-color: #fff;
  }

  .video-container .user-video {
    width: 30%;
    position: absolute;
    left: 10px;
    bottom: 10px;
    border: 1px solid #fff;
    border-radius: 6px;
    z-index: 2;
  }

  .video-container .partner-video {
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    top: 0;
    z-index: 1;
    margin: 0;
    padding: 0;
  }

  .video-container .action-btns {
    position: absolute;
    bottom: 20px;
    left: 50%;
    margin-left: -50px;
    z-index: 3;
    display: flex;
    flex-direction: row;
  }

  /*
## Emoji CSS Start
****************/
  .emoji_picker {
    position: absolute;
    display: flex;
    flex-direction: column;
    width: 20rem;
    height: 20rem;
    max-width: 100%;
    bottom: 55px;
    right: 0px;
  }

  .emoji_picker,
  .bottom_arrow {
    box-shadow: 0 0 5px 1px rgba(0, 0, 0, .0975);
  }

  .emoji_picker,
  .picker_container {
    border-radius: 0.5rem;
    background: white;
  }

  .picker_container {
    position: relative;
    padding: 1rem;
    overflow: auto;
    z-index: 1;
  }

  .category {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
    color: rgb(169, 169, 169);
  }

  .emojis_container {
    display: flex;
    flex-wrap: wrap;
  }

  .category button {
    margin: 0.5rem;
    margin-left: 0;
    background: inherit;
    border: none;
    font-size: 1.75rem;
    padding: 0;
  }

  .bottom_arrow {
    position: absolute;
    left: 82%;
    bottom: 0;
    width: 0.75rem;
    height: 0.75rem;
    transform: translate(-82%, 50%) rotate(45deg);
    background: white;
  }

  /*
## Emoji CSS End
****************/

  /* Mobiel Styles */
  @media only screen and (max-width: 768px) {
    .video-container {
      height: 50vh;
    }
  }
</style>